import base64, hashlib, json, re, sqlite3, zipfile
from pathlib import Path

BASE = Path(__file__).resolve().parent
ROOT = BASE.parent.parent
SOURCE = ROOT / '.codex-tmp/infinityfree-2026-09-12/wp-content'
db = sqlite3.connect('file:' + (ROOT / 'local-demo/site/wp-content/database/.ht.sqlite').as_posix() + '?mode=ro', uri=True)
db.row_factory = sqlite3.Row
def rows(table, where='1'):
    return [dict(r) for r in db.execute('SELECT * FROM wp_' + table + ' WHERE ' + where)]

# Only public content and presentation settings: never users, enquiries or credentials.
posts = rows('posts', "post_type IN ('page','attachment','nav_menu_item','custom_css','wp_global_styles','wp_navigation','wp_template') AND post_status IN ('publish','inherit')")
ids = {p['ID'] for p in posts}
for p in posts:
    p['post_author'] = 0
    p['post_password'] = ''
    p['comment_count'] = 0
data = {'posts': posts}
data['postmeta'] = [r for r in rows('postmeta') if r['post_id'] in ids and r['meta_key'] not in ('_edit_lock','_edit_last','_customize_changeset_uuid')]
data['term_relationships'] = [r for r in rows('term_relationships') if r['object_id'] in ids]
taxids = {r['term_taxonomy_id'] for r in data['term_relationships']}
data['term_taxonomy'] = [r for r in rows('term_taxonomy') if r['term_taxonomy_id'] in taxids]
termids = {r['term_id'] for r in data['term_taxonomy']}
data['terms'] = [r for r in rows('terms') if r['term_id'] in termids]
data['termmeta'] = [r for r in rows('termmeta') if r['term_id'] in termids]
data['fluentform_forms'] = rows('fluentform_forms', 'id = 3')
def remove_turnstile(v):
    if isinstance(v, list):
        return [remove_turnstile(x) for x in v if not (isinstance(x, dict) and x.get('element') == 'turnstile')]
    if isinstance(v, dict):
        return {k: remove_turnstile(x) for k,x in v.items()}
    return v
for form in data['fluentform_forms']:
    form['created_by'] = 0
    form['form_fields'] = json.dumps(remove_turnstile(json.loads(form['form_fields'])),ensure_ascii=False)
data['fluentform_form_meta'] = rows('fluentform_form_meta', "form_id = 3 AND meta_key IN ('formSettings','notifications','_primary_email_field','step_data_persistency_status','cws_managed_marker')")
for meta in data['fluentform_form_meta']:
    if meta['meta_key'] == 'notifications':
        val = json.loads(meta['value'])
        val['enabled'] = False
        meta['value'] = json.dumps(val,ensure_ascii=False)
for table in ['nextend2_section_storage','nextend2_image_storage','nextend2_smartslider3_sliders','nextend2_smartslider3_sliders_xref','nextend2_smartslider3_slides','nextend2_smartslider3_generators']:
    data[table] = rows(table)
data['nextend2_section_storage'] = [r for r in data['nextend2_section_storage'] if r['application']=='smartslider' and r['section'] in ('settings','sliderChanged')]
# Only these rows contain the saved slider styles. Inspect metadata before packing.
print('SLIDER SECTION KEYS', [(r['application'],r['section'],r['referencekey']) for r in data['nextend2_section_storage']])
option_names = {'blogname','blogdescription','show_on_front','page_on_front','page_for_posts','wp_page_for_privacy_policy','permalink_structure','theme_mods_hever-wpcom','sidebars_widgets','WPLANG','date_format','time_format','start_of_week','timezone_string','uploads_use_yearmonth_folders'}
data['options'] = [dict(option_name=r['option_name'],option_value=r['option_value']) for r in rows('options') if r['option_name'] in option_names or r['option_name'].startswith('widget_')]
payload = json.dumps(data,ensure_ascii=False,separators=(',',':')).encode()
# Detect known old integration secrets without printing their values.
secret_options = rows('options', "option_name IN ('_fluentform_turnstile_details','wp_mail_smtp','smtp','redis_server')")
for opt in secret_options:
    for secret in re.findall(r'[A-Za-z0-9_-]{28,}',opt['option_value']):
        if secret.encode() in payload and secret not in ['turnstile','use_auto_tls']:
            raise RuntimeError('Potential integration token found in exported data; review required')
assert not any(k in data for k in ['users','usermeta','fluentform_submissions','comments'])

out = BASE / 'packages'
out.mkdir(parents=True,exist_ok=True)
files = sorted(p for p in SOURCE.rglob('*') if p.is_file())
for p in files:
    name = p.relative_to(SOURCE).as_posix()
    assert name.startswith(('themes/hever-wpcom/','themes/varia-wpcom/','plugins/fluentform/','plugins/smart-slider-3/','mu-plugins/','uploads/2026/')), name
    assert not re.search(r'(wp-config|\.sqlite|\.sql$|\.env|debug\.log|private.?key)',name,re.I),name
    if p.suffix.lower() in ('.php','.html','.htm'): assert p.stat().st_size < 1000000,name
    assert p.stat().st_size < 10000000,name
    if p.suffix.lower() in ('.php','.js','.json','.txt','.pem','.css'):
        assert b'-----BEGIN PRIVATE KEY-----' not in p.read_bytes(),name

# Fixed manifest and archive hashes let the temporary admin tool accept only our reviewed files.
packs = []
batch=[]; size=0
for p in files:
    if batch and size + p.stat().st_size > 6500000:
        packs.append(batch); batch=[]; size=0
    batch.append(p); size += p.stat().st_size
if batch: packs.append(batch)
manifest={}
for i,batch in enumerate(packs,1):
    name = f'cws-files-{i:02d}.zip'
    path = out / name
    with zipfile.ZipFile(path,'w',zipfile.ZIP_DEFLATED) as z:
        for p in batch: z.write(p,p.relative_to(SOURCE).as_posix())
    manifest[name]={'sha256':hashlib.sha256(path.read_bytes()).hexdigest(),'files':{p.relative_to(SOURCE).as_posix():hashlib.sha256(p.read_bytes()).hexdigest() for p in batch}}
    print(name,len(batch),path.stat().st_size)
plug=BASE/'cws-restore'; plug.mkdir(exist_ok=True)
for name,obj in [('payload',data),('manifest',manifest)]:
    encoded=base64.b64encode(json.dumps(obj,ensure_ascii=False,separators=(',',':')).encode()).decode()
    (plug/(name+'.php')).write_text("<?php\ndefined('ABSPATH') || exit;\nreturn json_decode(base64_decode('"+encoded+"'), true);\n",encoding='utf-8')
with zipfile.ZipFile(out/'cws-restore.zip','w',zipfile.ZIP_DEFLATED) as z:
    for p in plug.iterdir(): z.write(p,'cws-restore/'+p.name)
print('DATA', {k:len(v) for k,v in data.items()})
print('PAYLOAD',len(payload),'TOTAL FILES',len(files),'PLUGIN ZIP',(out/'cws-restore.zip').stat().st_size)
