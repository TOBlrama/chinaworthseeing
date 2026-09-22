# 决策日志

录入日期：2026-08-09  
说明：本文件只记录项目总说明书中已经明确的决定，以及会阻塞后续工作的待确认事项。

## 已确认

| ID | 决定 | 影响 |
|---|---|---|
| D-001 | 建设面向海外游客的中国入境游网站 | 产品、内容和体验以外国游客视角为准 |
| D-002 | 第一阶段首要商业目标是获取高质量旅游询盘 | 转化质量优先于页面数与纯流量 |
| D-003 | 网站使用 WordPress | 后续主题、插件、部署围绕 WordPress 评估 |
| D-004 | WhatsApp 是第一阶段主要后续沟通渠道 | 询盘需要可联系的国家区号与手机号码 |
| D-005 | 第一阶段采用内容、获客、咨询、规划和人工转化模式 | 不默认建设完整在线交易闭环 |
| D-006 | English 是默认主要语言，并规划七种目标语言 | 信息架构与内容模型必须支持多语言 |
| D-007 | Notion 是内部资料系统，WordPress 是公开网站 | 两个系统默认解耦 |
| D-008 | 网站必须可迁移、可维护、可扩展 | 避免封闭主机和不可迁移技术绑定 |
| D-009 | 第一阶段避免过度工程化 | 复杂会员、OTA、实时库存、复杂 AI 与微服务不优先 |
| D-010 | 首批资料城市为北京、上海、广州、苏州、西安、桂林、杭州 | 首轮内容与路线研究以这些城市为主 |
| D-011 | 动态旅行信息需要日期意识与核验 | 内容流程必须支持核验日期或更新时间 |
| D-012 | MVP 主机采用 Cloudways Flexible，底层选择 DigitalOcean 2GB，纽约机房 | 开发、测试和生产环境围绕该主机建立；Cloudflare 继续管理域名和 DNS |
| D-013 | 第一版主导航按 Home、Inspirations、About、Contact 排列 | 四个页面作为全站一级入口；具体页面内容与结构仍需后续确认 |
| D-014 | 首页首屏采用暖白导航栏加满屏大图轮播的旅行杂志式方向 | 本地首屏已按视觉稿实施；公开文案与语言入口仍待确认 |
| D-015 | 首页 Hero 后使用已确认的 ChinaWorthSeeing Brand Statement | 首页第二屏采用居中窄文本列；使用项目负责人提供的标题和四段英文正文 |
| D-016 | Brand Statement 后使用已确认的 Why travel with ChinaWorthSeeing 三卡区块 | 首页以 Natural Wonders、History & Heritage、Flavours of China 三个主题继续建立目的地吸引力 |
| D-017 | 收紧 Hero 与 Brand Statement 的视觉衔接，并统一 Why Travel 标题组间距 | 保留 Hero 黑色底条；移除过大的 Brand Statement 顶部留白；眉题到主标题与主标题到卡片使用相同间距 |
| D-018 | Why Travel 后新增七城市横向 Destination Carousel | 通过竖版摄影、部分露出的下一张卡片和原生横向滑动，引导访客开始探索具体城市 |
| D-019 | 以 Explore China 取代 Inspirations，并建立 Places to Go、Experiences、Plan Your Trip 信息架构 | 主导航、首页卡片、Hub 页面与目的地/主题/指南页面使用同一套 WordPress 页面和链接体系 |
| D-020 | Hero 与 Brand Statement 之间新增 What We Do 服务说明区块 | 首次访问者可明确理解当前提供行程规划与咨询、不代订旅游产品的服务边界 |
| D-021 | 将首页 What We Do 简化为两层文字 | 删除复杂双栏、编号价值点和独立说明模块，以更直接的主副标题传达服务及预订边界 |
| D-022 | 微调 What We Do 与 Brand Statement 的色彩和字体 | 统一 What We Do 与导航暖米色背景，明确按钮大小写，并将 Brand Statement 改为纯白背景 |
| D-023 | Contact 使用 Black Tomato 信息层级的英文询盘表单，并由 Fluent Forms 保存和通知 | 在 WordPress 后台形成可维护线索，通知发送至个人 Gmail，后续人工通过 Email 或 WhatsApp 跟进 |
| D-024 | 发布英文 Privacy Policy，并采用提交即确认阅读的隐私提示 | 表单明确告知数据用途、保留、共享、权利与跨境处理；Cloudflare Turnstile 待密钥配置后启用 |
| D-026 | 本地 WordPress 已部署到 Cloudways 临时网址并完成基础上线验证 | 临时环境可供验收；正式域名、DNS、SMTP 与其余上线项仍需单独确认 |
| D-028 | 暂停 Cloudways 生产托管并停用 Elastic Email | 网站暂时下线以停止持续费用；已保留可恢复的完整 WordPress 备份，恢复运营时无需重建前端 |

### D-012｜MVP 主机方案

- 确认日期：2026-08-10
- 结论：采用 Cloudways Flexible；底层云服务商为 DigitalOcean；初始规格为 2GB RAM、1 vCPU、50GB 存储、2TB 流量；机房选择 New York。
- 原因：目标访客以美国和欧洲为主；方案支持按月计费、一键测试环境、SSH/SFTP、数据库访问、免费 SSL 和可迁移的标准 WordPress；基础价格在每月 100–150 元人民币预算内。
- 替代方案：Hostinger 操作更简单但优惠通常要求多年预付；DreamHost 成本较低但普通方案缺少一键测试站；SiteGround 带测试站方案的续费价格超出预算。
- 成本边界：核验时基础价格为 11 美元/月；异地备份按存储量另计，税费与汇率可能变化。付费 Cloudflare、SafeUpdates、邮箱和其他附加服务不自动纳入。
- 迁移影响：WordPress 文件、数据库和媒体必须保持可导出；不得依赖 Cloudways 专有功能才能运行。未来可迁往其他标准 WordPress 主机，不应要求重建网站。
- 实施边界：域名继续保留在 Cloudflare；网站通过测试和上线检查前不得修改正式 DNS。购买、支付、账户凭据和 DNS 变更仍需项目负责人另行操作或明确授权。

### D-013｜第一版主导航

- 确认日期：2026-08-10
- 结论：第一版主导航依次为 Home、Inspirations、About、Contact；四个页面已在本地 WordPress 建立，Home 已设为静态首页。
- 当前边界：本决定仅确认导航名称、顺序与页面入口，不代表首页、Inspirations、About 或 Contact 的内容结构、公开文案和视觉样式已经确认。
- 迁移影响：导航使用 WordPress 原生页面与菜单功能，不依赖封闭页面构建器，可随标准 WordPress 数据一起迁移。
- 状态说明：本决定中的 `Inspirations` 名称和四项顺序已由 D-019 取代；保留本条作为历史记录。

### D-014｜首页首屏视觉方向

- 确认日期：2026-08-10
- 结论：采用 `docs/design/homepage-hero-concept-v1.png` 所示方向；顶部为暖白色独立导航栏，下方为接近满屏的大图 Hero，左侧叠加标题、说明和双按钮，背景使用 8 张图片自动轮播。
- 页头细化：导航栏固定在视口顶部；原始实现为 Home、Inspirations、About、Contact 与 Start Planning 整组右对齐。D-019 后将 Inspirations 替换为 Explore China；当前页面仍不改变文字颜色，只显示绿色下划线。
- 参考边界：只借鉴参考站的版式层级和视觉气质，不复制其代码、照片、品牌或原始文案。
- 当前实施：本地 WordPress 已使用 Smart Slider 3 建立 8 图轮播，配置 6 秒自动播放、800ms 交叉渐变、左右箭头和 8 个圆点；图片使用项目负责人提供的 8 张照片并生成 1920 × 1080 WebP 优化副本，原图未改动。
- 当前边界：本地首屏英文文案和 `Start Planning` 链接属于视觉原型文案，不代表品牌主张、服务承诺或正式转化路径已经确认；除 D-015 Brand Statement 与 D-016 Why Travel 区块外，语言入口和首屏以下的其余首页内容结构仍未确认。
- 迁移影响：实施时必须保证图片、文案、按钮和轮播顺序可在 WordPress 后台维护，并保持标准 WordPress 导出与迁移路径。

### D-015｜首页 Brand Statement

- 确认日期：2026-08-11
- 结论：首页 Hero 后紧接 Brand Statement；标题为 `China should feel easier to explore`，正文使用项目负责人于 2026-08-11 提供的四段英文文案；正文下方增加 `get in touch` CTA，并进入 `/contact/`。
- 版式：参考 Black Tomato 首页同类区块的层级关系，采用全宽浅色背景、居中窄文本列、小号高字距标题和分段正文；不复制对方字体、背景图片、按钮、代码或品牌资产。
- 当前实施：内容使用 WordPress 原生 Group、Heading、Paragraph、Buttons 和 Button 区块，样式追加在 WordPress“额外 CSS”中；桌面文本列最大宽度 900px，移动端保留居中排版并使按钮扩展到文本列宽度。
- 当前边界：本决定只确认该首页区块的文案、CTA 和版式，不自动确认其他品牌主张、服务承诺、价格、资质或首页后续区块；P-001、P-003、P-004、P-007 和 P-010 的其余部分继续待确认。
- 迁移影响：内容和样式均随标准 WordPress 数据导出；未修改 Hever 父主题，也未新增封闭页面构建器依赖。

### D-016｜首页 Why travel with ChinaWorthSeeing

- 确认日期：2026-08-11
- 结论：Brand Statement 后紧接 `Why travel with ChinaWorthSeeing` 区块；眉题为 `A LAND OF STORIES, SCENERY & FLAVOUR`，下方依次展示 Natural Wonders、History & Heritage、Flavours of China 三张图文卡片，并使用项目负责人提供的英文正文。
- 版式：参考 Safari Frank 的同类区块层级，采用居中眉题和主标题、桌面三列卡片、手机单列卡片、上图下文和统一图片裁切；只借鉴信息层级与交互效果，不复制对方代码、图片、字体或品牌资产。
- 当前实施：使用 WordPress 原生 Group、Heading、Image 和 Paragraph 区块，样式保存在 WordPress“额外 CSS”；三张 1800 × 1200 原图保持不变，另生成 WebP 优化副本并进入媒体库。
- 当前边界：本决定只确认该区块的文案、图片顺序与版式，不代表已确认正式销售服务、价格、资质、履约承诺或首页其他后续区块；P-001、P-003、P-004、P-007 和 P-010 的其余部分继续待确认。
- 迁移影响：内容、媒体和样式均保留标准 WordPress 导出路径；未修改 Hever 父主题，未删除或替换 Smart Slider。

### D-017｜首页区块垂直间距细化

- 确认日期：2026-08-11
- 结论：Hero 现有黑色底条保持不变，Brand Statement 必须直接承接 Hero，不保留额外 section 间隙；Brand Statement 仍保留正常区块留白，但不得以过大的顶部 padding 形成视觉断层。
- Why Travel 间距：`A LAND OF STORIES, SCENERY & FLAVOUR` 到主标题、主标题到三张图片卡片使用同一垂直间距；桌面为 18px，手机为 16px。
- 当前实施：实页检查确认两个 section 的边界间距原本已为 0px，且不存在 Spacer；视觉空白来自 Brand Statement 的顶部 padding。该顶部 padding 调整为桌面响应式 40–56px、手机 32px，标题、正文段落间距和底部 padding 保持原值。
- 技术边界：不使用负 margin；不修改 Hero 尺寸、Smart Slider、Hever 父主题或 Brand Statement 文案。

### D-018｜首页城市 Destination Carousel

- 确认日期：2026-08-11
- 结论：在 Why Travel 三卡区块之后新增 `Explore China, City by City`，眉题为 `PLACES WORTH DISCOVERING`；第一版按 Beijing、Shanghai、Xi'an、Guilin、Hangzhou、Suzhou、Guangzhou 顺序展示七个城市。
- 版式：采用摄影主导的横向 editorial carousel；卡片图片统一为 3:4 竖版，不使用边框、阴影、大圆角、评分、价格或 OTA 产品信息。桌面约显示 3.35 张、平板 2.25 张、手机 1.15 张，并保留下一张卡片的部分露出。
- 交互：卡片轨道本身是直接拖动面，不显示左右按钮；桌面可在图片或文字区域按住鼠标直接拖动，并保留键盘方向键；移动端支持原生横向滑动。使用 CSS scroll snap，尊重 reduced-motion，并确保轮播内部滚动不会形成整页横向溢出。
- 当前实施：内容使用可编辑的 Gutenberg Group、Image、Heading 和 Paragraph 区块；样式保存在 WordPress“额外 CSS”；渐进增强交互由独立 `cws-home-destinations` MU 插件提供，不修改 Hever 父主题。
- 图片与性能：七张项目负责人提供的 1200 × 1600 PNG 原图保持不变；另生成 WebP 优化副本并导入媒体库，使用 WordPress `srcset`、`sizes` 和 lazy loading。
- 链接边界：本决定初次实施时七个城市入口为 `#` placeholder；D-019 已建立对应 Destination 页面并替换全部入口。
- 当前边界：只确认该城市区块，不自动确认首页后续区块或城市详情页结构；现有 Hero、Brand Statement 和 Why Travel 区块不得因本区块实施而改变。

### D-019｜Explore China 信息架构与导航

- 确认日期：2026-08-11
- 结论：一级导航依次为 Home、Explore China、About、Contact、Start Planning；Explore China 在主导航中只作为不可点击的文本标签，由独立按钮展开 Mega Menu。Places to Go、Experiences、Plan Your Trip 三个栏目标题也作为不可点击文本，其下具体页面名称继续作为链接。`/explore-china/` Hub 页面本身继续保留。
- URL：Hub 使用 `/explore-china/`；目的地使用 `/destinations/[slug]/`；Experience 使用 `/experiences/[slug]/`；实用指南使用 `/plan-your-trip/[slug]/`。Places to Go 不绑定城市概念，未来可继续容纳自然目的地和地区。
- 当前范围：Places to Go 只包含已确认的 Beijing、Shanghai、Xi'an、Guilin、Hangzhou、Suzhou、Guangzhou；Experiences 只包含 Natural Wonders、History & Heritage、Flavours of China；Plan Your Trip 只包含 First Time in China、When to Visit、Getting Around、Payments & Apps、Entry & Visa。
- 交互：桌面端使用克制的宽幅 Mega Menu，Explore China 和三个栏目标题本身不可点击；展开按钮支持鼠标、触摸与键盘。移动端使用独立 accordion，不依赖 hover。支持 Escape、`aria-expanded`、`aria-controls` 和明确的 focus-visible 状态。桌面端普通一级链接使用与 55px 页头等高的 flex 点击区域，确保文字垂直居中。
- 首页连接：七个城市卡片直达同一套 Destination 页面；Why Travel 三个主题标题直达同一套 Experience 页面，不增加中间点击层，也不创建重复页面。
- 当前实施：页面和导航数据存储在 WordPress 原生 Page 与 Primary Navigation menu；Featured 内容从 First Time in China 页面的标题、摘要和特色图读取。交互由 `cws-explore-navigation` MU plugin 渐进增强，样式位于 WordPress“额外 CSS”，未修改 Hever 父主题或 Smart Slider。
- 内容状态：Explore China、Destinations、Experiences、Plan Your Trip 四个入口页已建立结构；七个目的地、三个 Experience 和五个 Guide 子页当前仍为诚实 placeholder，不包含虚构旅游正文。
- 当前边界：本次不重做现有 Hero、Brand Statement、Why Travel 或 Destination Carousel；不确认新目的地、酒店、路线、价格、评分、正式旅游承诺或各 placeholder 页的完整页面模板。

### D-020｜首页 What We Do 服务说明

- 确认日期：2026-08-11
- 结论：在首页 Hero 后、Brand Statement 前新增 `What We Do`。正式英文内容使用项目负责人提供的主标题、简介、三个价值点、`We Plan. You Stay in Control.` 服务边界说明和 `Your trip. Your choices. Our planning.` 收束语。
- 服务边界：ChinaWorthSeeing 当前提供个性化行程规划与旅行咨询；帮助客户确定目的地、停留时长、城市顺序、每日路线、交通衔接、预约提醒、建议住宿区域和实用旅行准备。当前不代订酒店、火车、景点门票或其他旅游产品，也不暗示提供导游、司机、接送或旅行套餐。
- 版式：采用无图片、无图标、无额外 CTA 的深色 editorial split layout；桌面端以标题简介和 01/02/03 编号价值点形成双栏，下方用独立横向说明呈现透明度与客户控制权；移动端重排为清晰单列，不使用 SaaS 卡片。
- 当前实施：内容使用 WordPress 原生 Group、Heading 和 Paragraph 区块；样式保存在 WordPress“额外 CSS”；不增加 JavaScript、外部库、图片或父主题修改。
- 当前边界：只确认本区块的文案、服务边界、首页位置与版式；不确认价格、付款、正式产品包装、代订能力或其他服务。Hero、Brand Statement、Why Travel 和 Destination Carousel 的内容与内部设计保持不变。
- 状态说明：原双栏、01/02/03 价值点和独立服务边界版式已由 D-021 取代；本条保留为历史记录。

### D-021｜首页 What We Do 极简化

- 确认日期：2026-08-12
- 结论：保留 `Hero → What We Do → Brand Statement → Why Travel → Destination Carousel` 顺序，但将 What We Do 替换为只有主标题和副标题的极简服务说明。
- 正式文案：主标题为 `We create detailed, personalized travel plans — without selling you a packaged tour.`；副标题为 `Most bookings can be made through links included in your itinerary, or you're always free to book everything yourself.`。
- 服务边界：当前只提供定制旅行计划和旅行咨询，不代订酒店、火车票、门票或其他旅游产品；行程可以提供购买链接，客户也可以自行选择其他渠道购买。
- 版式：复用现有深色背景、Georgia 展示字体、PT Sans 正文字体和首页内容宽度；主标题大号居中，副标题明显更小，并保留充足留白。删除眉题、双栏、01/02/03、卡片、图标、图片、CTA 和独立说明模块。
- 当前边界：不修改 Hero、Brand Statement、Why Travel 或 Destination Carousel 的内容与内部样式，不新增动画、JavaScript、图片或外部依赖。

### D-022｜首页 What We Do 与 Brand Statement 视觉微调

- 确认日期：2026-08-12
- What We Do：背景改为与导航栏完全相同的暖米色（`#f4efe5`）；主标题和副标题都使用与 Brand Statement 标题相同的无衬线字体族，保留原有大标题与小副标题层级，不将句式文案强制改为全大写。
- Brand Statement：背景改为纯白色（`#fff`）；按钮文案改为 `GET IN TOUCH`，链接和按钮尺寸保持不变。
- 当前边界：只调整本次指定的颜色、字体和按钮文字；不修改其他首页区块或信息架构。

### D-023｜Contact 询盘表单与线索流转

- 确认日期：2026-08-12
- 页面与设计：询盘入口位于一级导航 `Contact` 的 `/contact/` 页面；参考 Black Tomato Make an Enquiry 的信息层级，使用居中开场、`YOUR TRIP` 与 `YOUR DETAILS` 两个浅灰表单区、桌面右侧联系卡、平直白色控件和方形主按钮。只借鉴布局和视觉关系，不复制其代码、图片、品牌字体、粉色品牌色、预算字段、Office Hours 或 Newsletter。
- 品牌适配：主按钮与交互色使用 ChinaWorthSeeing 绿色 `#315f52`，右栏使用深绿色；桌面双栏、900px 以下单栏，移动端按钮全宽。
- 字段：First name、Last name、Email address、WhatsApp country code、WhatsApp number、Destination multi-select、Expected travel month、Expected year、Trip duration、Traveller count 为必填；Additional comments 为可选。目的地包括 Beijing、Shanghai、Xi'an、Guilin、Hangzhou、Suzhou、Guangzhou、Chengdu、Chongqing 和 `Not sure yet / I'd like your advice`。
- 数据去向：使用现有 Fluent Forms。提交记录保存在 WordPress 后台，并启用到 `felixsheng27@gmail.com` 的管理员通知，Reply-To 使用客户填写的邮箱；后续由运营者人工通过 Email 或 WhatsApp 跟进。
- 当前实施：本地完整提交测试已成功生成后台记录；测试期间拦截了实际邮件，验证后删除了唯一测试记录和临时拦截器。正式 Gmail 投递、域名邮箱、SMTP、SPF 与 DKIM 尚未验证，不能把“已配置通知”表述为“生产邮件投递已可靠”。
- 技术与迁移：表单数据使用 Fluent Forms 数据表，页面使用 WordPress 原生 Group 与 Shortcode 区块，展示样式由 `cws-contact-enquiry` MU plugin 提供；不修改 Hever 父主题，不引入封闭页面构建器。
- 兼容修正：Contact 原先被 WordPress 错误指定为文章列表页，导致页面内容被博客列表覆盖；现已解除 `page_for_posts` 关联，使 `/contact/` 作为普通页面渲染。
- 交互细化：城市多选标签的删除按钮固定为紧凑的 18px 控件，不再继承主题通用按钮高度而撑大标签和选择框；月份、年份、旅行时长和人数的 `Select...` 文字仅作为未选择时的占位提示，展开菜单时不作为可选项显示。
- Contact 页面细化：隐藏该页面的 Hever 默认文章页脚和站点页脚，因此不显示主题页脚；D-025 后其他页面的默认 Footer 小工具也已全站删除。`Your trip`、`Your details` 及全部字段标签由 13px 调整为 15px，字重和输入值、占位符、选项文字的字号保持不变。
- WhatsApp 国家前缀：原自由输入框改为必填的可搜索、可滚动国家区号下拉；关闭状态显示国旗和区号，展开后显示国旗、国家/地区名称和区号。首屏优先排列 United Kingdom、United States、Canada、France、Switzerland、Germany、Japan、Singapore、China，其余继续提供完整列表；后台保存 `ISO +区号`，与 WhatsApp number 继续分字段保存。不新增电话插件或外部国家选择组件。

### D-024｜Privacy Policy、同意提示与数据边界

- 确认日期：2026-08-12
- 主体与联系方式：公开文案为 `ChinaWorthSeeing is operated by Yixiang Sheng, an individual based in China.`；只公开 `felixsheng27@gmail.com`，不公开地址。品牌统一写作 `ChinaWorthSeeing`，域名为 `www.chinaworthseeing.com`。
- 表单提示：Send enquiry 上方显示 `By submitting this form, you confirm that you have read our Privacy Policy and understand that we will use your information to respond to your enquiry.`；其中 `Privacy Policy` 为粗体、下划线链接并进入 `/privacy-policy/`。
- 隐私页面：英文 Privacy Policy 于 2026-08-12 发布并设为 WordPress 官方隐私政策页，覆盖收集字段、技术日志、用途、必要字段、处理依据、服务商和旅行供应商共享、跨境处理、Cookies、反垃圾、保存期限、用户权利、儿童及同行人员、安全和更新。
- 营销与自动化：不发送营销信息，不启用 Newsletter，不使用 Google Analytics、Meta Pixel、广告重定向、自动定价或自动决策，不出售、出租、交换数据，也不将询盘数据用于训练 AI。
- 供应商共享：只在客户明确要求或服务确认后，向旅行供应商提供完成合作所需的最少资料。
- 保存：非活跃或未成交询盘一般 3 个月；完成服务一般 1 个月；安全日志和备份一般 30 天；投诉、争议、欺诈调查或法律义务可例外延长。当前 WordPress 尚不能自动区分未成交与完成服务，因此需要运营状态和定期删除流程，不能声称已完全自动删除。
- 用户权利：访问、更正、删除等请求发到公开邮箱，目标在 30 天内回复；网站面向成年人，初步询盘由成年人提交。
- 防垃圾：选择 Cloudflare Turnstile。当前没有站点密钥和私钥，表单脚本只会在 Fluent Forms 确认密钥有效后加入 Turnstile 字段；当前本地表单未启用且未对外声称已启用。
- 当前边界：此页面是基于已确认运营事实形成的项目隐私说明，不替代上线国家或地区的专业法律意见；上线前应按实际启用的服务商、Cookies、主机和业务流程复核。

### D-025｜全站默认 Footer 小工具清理

- 确认日期：2026-08-12
- 结论：删除 Hever/Varia `sidebar-1` 中的默认搜索、Recent Posts、Recent Comments、Archives、Categories 小工具，以及未启用的默认空 Banner 小工具；这些内容不再出现在 About、Explore China、Destination、Experience、Guide 或其他普通页面底部。
- 保留内容：普通页面继续保留主题页脚中的站点名称和 Privacy Policy；Contact 页面沿用 D-023 的独立无主题页脚展示方式。
- 数据边界：本次删除的是默认 Footer 小工具实例，不删除 `Hello world!` 文章、默认评论或 Uncategorized 分类本身；它们只是不会再通过页脚小工具向访客展示。
- 技术与迁移：通过可重复执行的 WordPress 清理脚本更新 `sidebars_widgets` 与 `widget_block` 选项，不修改 Hever/Varia 父主题；另有只读验证脚本检查 Footer 小工具区保持为空。

### D-026｜Cloudways 临时网址部署

- 确认日期：2026-08-13
- 结论：本地 WordPress 已部署至 `https://wordpress-1659769-6611899.cloudwaysapps.com/`；Cloudways 应用级文件与数据库备份已在覆盖前创建，正式域名与 Cloudflare DNS 未修改。
- 实施结果：远程 WordPress 7.0.4、PHP 8.2；Hever、Fluent Forms、Smart Slider 3、自定义 MU 插件、页面、媒体与数据库已迁移。Studio SQLite 文件未带入生产环境；数据库排序规则已转换为 Cloudways 数据库支持的 `utf8mb4_unicode_ci`。
- 验证结果：首页、Contact、代表性 Destination、Experience、Plan Your Trip 页面与 WordPress REST API 均通过 HTTPS 返回 200；数据库检查通过；Fluent Forms 已完成一次拦截真实邮件的端到端提交测试，成功状态与数据库保存正常，测试提交和临时邮件拦截文件随后已删除。
- 安全与清理：本次部署使用的临时 SSH 公钥已撤销并确认不能再次连接；服务器和本机临时迁移包已删除。Cloudways 应用级回滚备份继续保留。
- 当前边界：临时网址响应头仍为 `noindex, nofollow`，适合验收而非正式收录；真实通知邮件投递、SMTP、Cloudflare Turnstile、长期缓存方案、正式域名绑定、SSL 与 DNS 切换尚未确认或验证，不视为正式生产上线完成。

### D-027｜正式域名英语 MVP 上线与索引边界

- 确认日期：2026-08-16
- 确认与授权：项目负责人明确要求正式上线，并在执行前确认允许添加一次性 SSH 公钥、修改公开生产网站，以及完成后删除三把相关公钥和全部临时文件。
- 上线范围：当前公开 MVP 以 English 为默认生产语言；Home、About、Contact、Explore China 与 Privacy Policy 允许搜索引擎收录。其他语言的首发范围仍属 P-006，不创建空翻译页或低质量机器翻译页。
- 内容边界：15 个 Destination、Experience 与 Plan Your Trip 子页继续公开可访问，但在正式正文完成前保持页面级 `noindex`；其内部验收提示已替换为面向访客的诚实说明。Explore China 入口页虽属结构页，但作为正式导航入口允许收录。
- 隐私与询盘：Privacy Policy 已按实际生产服务商复核并恢复；About 已补充不含未确认资质、价格或服务承诺的正文；Contact 保持 Fluent Forms、WhatsApp 国家区号、Cloudflare Turnstile 和 Gmail 通知配置。
- SEO 与语言结果：全站搜索可见性已开启，WordPress 默认语言为 `en_US`，正式页面不再输出全站 `noindex`，WordPress sitemap 已恢复；placeholder 页面由可移植 MU Plugin 保持 `noindex`。
- 基础设施结果：使用 2026-08-16 11:58:08 UTC 的 Cloudways 应用文件与数据库备份作为回滚点；Cloudflare 保持 `Full (strict)`，DNS 与 Email Routing 本次未修改。
- 安全清理：两把 2026-08-13 旧 SSH 公钥和本次一次性公钥均已从 Cloudways 删除，服务器与本地临时部署文件已删除；生产仅保留版本化的 MU Plugin。
- 尚未完成：未提交真实生产合成询盘，因此 Gmail 实际收件、提交成功状态、后台新记录和 Reply-To 全链路仍待单独确认与验证，不得写成已完成。
- 生产邮件故障与修复：项目负责人随后人工提交的询盘成功保存为 Fluent Forms 记录，但 WP Mail SMTP 报告 Elastic Email `APIKey Expired`。经再次授权，邮件通道已从失效的应用级 Elastic Email API Key 切换为 Cloudways 已启用的服务器级 Elastic Email（WP Mail SMTP Default/PHP mailer）；插件测试邮件发送后不再出现失败状态。此修复不新增服务、密钥、DNS 或插件费用，可切回原配置。
- 验证边界：自动化合成提交被 Turnstile 拦截且未创建记录，未绕过验证码；Fluent Forms Lite 不提供现有记录通知重发。仍需项目负责人确认测试邮件实际到达 Gmail，并由真人再次提交表单以完成 Gmail 与 Reply-To 验证。

### D-028｜暂停 Cloudways 托管与 Elastic Email

- 确认日期：2026-08-20
- 确认与授权：项目负责人确认永久删除 Cloudways 的 `My First Server` 并停用 Elastic Email，原因是未来数月暂不运营网站。
- 实施结果：唯一的 DigitalOcean 2GB New York 服务器及其唯一应用 `ChinaWorthSeeing` 已从 Cloudways 列表删除；Elastic Email 1000 Emails 订阅已停用。已产生的当月使用费仍按 Cloudways 后付费账期结算。
- 数据保护：删除前已创建、下载并校验完整 WordPress 备份，位于 `backups/cloudways-cancel-2026-08-20/`；归档包含 `public_html/wp-content/` 与数据库 SQL。一次性 SSH 私钥、公钥及服务器授权已删除。
- 恢复边界：恢复运营时新建标准 WordPress 主机，恢复该归档并重设 DNS、SSL、邮件服务与询盘测试即可；无需重新设计或重建前端。删除服务器后 Cloudways 的托管备份仅保留 14 天，本地归档是长期恢复副本。

### D-029｜毕业设计完整系统展示与后续运营复用

- 确认日期：2026-09-09
- 已确认：项目负责人拟将网站用于毕业设计，要求可在线访问、真实后台登录、询盘数据库保存和真实邮件发送；希望免费部署，并使用 GitHub。毕业设计完成后保存定稿，后续从该版本继续改进并迁回 Cloudways 运营。
- 已核验：2026-08-20 的独立归档仍存在，SHA-256 与 D-028 备份一致；包含 10,110 个条目、WordPress 文件和数据库 SQL，条目文件大小合计 208,344,202 字节。
- 技术边界：GitHub Pages 仅提供静态托管，不支持 WordPress 所需 PHP/MySQL，因此仅部署到 Pages 不能满足已确认的完整系统要求。
- 建议：GitHub 管理经过检查的项目代码；另用支持 PHP/MySQL 的主机运行完整 WordPress，独立配置邮件。毕设提交时创建固定版本标记并另存完整备份，后续运营开发从该版本延续。
- 待确认：是否接受另用免费 PHP/MySQL 主机，具体供应商及邮件服务；当前尚未创建或发布 GitHub 仓库，也未恢复线上网站。InfinityFree 仅为候选，单文件大小限制及 SMTP 必须实际核验后才能认定适用。
- 数据边界：完整数据库归档、后台账号、询盘个人信息和密钥不进入公开仓库；恢复与代码版本管理分别处理。

### D-030｜学校验收范围、阶段源码与本地恢复

- 确认日期：2026-09-10。
- 已确认：学校截图允许“在线访问或本地部署”，要求用户系统、数据管理和至少三项核心业务功能；Vue/React 等为鼓励采用，不是截图中的强制技术栈。项目负责人要求先核查功能，并将当前开发版放入 GitHub、后续补全；在线访问意愿不因本地恢复而自动取消。
- 实施结果：独立 `local-demo/site/` 已恢复 WordPress 文件及数据库，Studio sandbox/PHP 8.2 启动；五个主要页面 HTTP 200。原运营备份与 D: 旧站未覆盖。本地阻止 wp_mail 对外发送并设为不收录。
- 核查结论：23 个已发布页面中 15 个仍为占位；有后台用户角色、内容管理和询盘存储，尚不能证明至少三项独立核心业务已完成。详见 `docs/reviews/graduation-requirements-2026-09-10.md`。
- 代码状态：已整理 27 文件的 `graduation-source/` 开发版和 README，排除数据库、凭据和完整运营备份；GitHub 网页尚需登录，仓库未创建、未上传，公网网站未上线。
- 建议：先完成独立本地副本的端到端验收，代码持续版本管理；第三项业务可考虑个人旅行计划管理。新增模块尚未获实施确认。
- 待确认：指导老师是否认可管理员用户系统及 CMS/插件开发深度；新业务范围、真实邮件服务及公网运行环境。不能把本地页面加载成功当成学校第一项完整达标。

### D-031｜InfinityFree 免费公网展示主机

- 确认日期：2026-09-11。
- 已确认：用户已创建 InfinityFree 免费主机，授权把现有网站迁移到该主机，供互联网访问。免费网址为 `chinaworthseeing.wuaze.com`；不新增付费服务，不自动更改正式域名 DNS。
- 状态说明：本条记录 2026-09-11 的初始建站决定；后续安装与恢复结果见 D-032。
- 数据边界：使用独立脱敏展示副本，保留原运营归档和本地站，不把旧询盘、旧凭据直接搬入新站。具体进度见 `docs/launch/infinityfree-migration-2026-09-11.md`。

### D-032｜本次只恢复已有网站到免费主机

- 确认日期：2026-09-13。
- 已确认：项目负责人要求先把已有网站恢复到 InfinityFree 免费主机；学校验收口径、新功能、内容补充及其他工作暂缓，后续再继续。
- 完成日期：2026-09-16。
- 实施结果：已将既有网站恢复到 `https://chinaworthseeing.wuaze.com/`；导入 12 个已校验文件包和 2,738 个站点文件，恢复 23 个已发布页面、Hever 主题、导航、首页轮播、媒体及 Fluent Forms 询盘表单。新管理员得到保留，未导入旧用户、旧询盘、评论、会话或旧凭据。
- 实施边界：复用已确认的独立脱敏迁移范围，保留新管理员、原运营归档和本地站；不变更正式域名 DNS，不引入付费服务。迁移完成不等于毕设验收通过或邮件接单链路完成。
- 迁移插件授权：用户随后明确允许安装并使用临时迁移插件、上传网站，完成后停用。
- 验证结果：公开 HTTPS 首页、Contact、About、Explore China、Privacy Policy 和 Beijing 页面均可访问；首页轮播、导航、主要版块、图片和 Contact 表单可见。临时迁移插件已停用并保留为未启用状态。
- 后续边界：询盘邮件通知与 Turnstile 尚未配置，未提交真实询盘；当前结论仅为网站恢复并可公开访问，不代表邮件收件、正式接单或学校验收已完成。详见迁移记录的 2026-09-16 完成记录。

### D-033｜换机交接、GitHub Private 与离线备份

- 确认日期：2026-09-22。
- 已确认：项目负责人换电脑前，需要把 ChinaWorthSeeing 的代码、文档、设计稿和图片素材整理到 GitHub Private，并在 U 盘另存一套能够继续毕设开发和未来运营恢复的完整副本。
- GitHub 边界：私有仓库收录项目文档、自定义 WordPress 源码、设计资源、原始图片素材和已经审查的脱敏网站恢复包；不提交 `.env`、`wp-config.php`、真实数据库、旧询盘、后台账号、SMTP/Turnstile/API 凭据、完整运营归档或私钥。
- 离线边界：U 盘副本保留完整工作区、原始 D 盘 WordPress 站点及图片、Cloudways 完整归档、SQL、SQLite 和本地配置，用于灾难恢复。该副本含账号配置和潜在个人数据，必须视为敏感资料，不得直接上传或共享。
- 恢复目标：GitHub 克隆后可以继续源码、内容结构和设计工作，也可用脱敏恢复包重建当前公开展示站；未来正式运营恢复须使用离线完整归档，并重新配置数据库凭据、邮件、Turnstile、DNS、SSL 和端到端询盘测试。
- 实施结果：GitHub Private 仓库已建立并上传到 `https://github.com/TOBlrama/chinaworthseeing`；仓库包含项目源码、文档、设计稿、原始图片及脱敏恢复包。完整离线副本已写入 `F:\think book备份\ChinaWorthSeeing-2026-09-22`，工作区与 D 盘旧站的文件数和总字节均与源一致，五项关键恢复文件 SHA-256 一致，离线 Git bundle 验证为完整历史。

## 待确认

| ID | 待确认事项 | 为什么会阻塞后续工作 |
|---|---|---|
| P-001 | 品牌名称、定位、核心承诺与差异 | 决定公开文案、视觉与域名 |
| P-002 | 最核心国家、年龄、预算和客户类型 | 决定语言、内容、渠道和服务包装 |
| P-003 | 已确认规划与咨询方向后的具体产品包装、交付物与收费方式 | 决定服务页、询盘资格、报价和履约流程 |
| P-004 | 服务价格、报价、退款与履约规则 | 决定是否展示价格和交易流程 |
| P-005 | MVP 是否在线付款及支付方式 | 决定合规、插件和技术范围 |
| P-006 | 首发语言范围 | 决定发布计划、翻译和 QA 工作量 |
| P-007 | 除已确认 Explore China 信息架构、首页首屏、What We Do、Brand Statement、Why Travel、城市 Destination Carousel 与 Explore Hub 结构外的首页及主要页面内容结构 | 决定完整视觉概念和内容生产 |
| P-009 | 主题、多语言、SEO、缓存和其余安全方案；主机由 D-012 确认，表单与 Turnstile 方向由 D-023/D-024 确认 | 决定其余 WordPress 架构与持续成本 |
| P-010 | 首页首屏以外的品牌视觉系统、Logo 与主要页面风格 | 决定完整设计系统、图片与页面实现 |
| P-011 | 法律主体、服务资质和供应商履约边界 | 决定可公开承诺的服务与条款 |

## 变更规则

每次确认重要事项时：

1. 为决定分配新的 D 编号
2. 记录确认日期、结论、原因、替代方案与迁移影响
3. 将对应 P 项移出待确认表
4. 同步更新 MVP 需求和路线图

不得仅依据 AI 建议自动把 P 项改成 D 项。
