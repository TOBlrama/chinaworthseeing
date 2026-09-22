# InfinityFree 毕设公网迁移 — 2026-09-11

状态：2026-09-16 已将既有网站恢复到 InfinityFree，公开页面可通过 HTTPS 访问；邮件通知与 Turnstile 尚未配置或验收。

- 用户已注册并创建免费主机，明确授权操作迁移。
- 已在 Edge 核验账户 `if0_42891756` 为 Active，免费域名 `chinaworthseeing.wuaze.com`，目录 `htdocs`。
- 已进入控制面板与 Softaculous WordPress 安装页；面板显示 MySQL 数据库 0 个。MySQL 主机 `sql203.infinityfree.com`。
- 已填写站点名称 ChinaWorthSeeing、说明 Graduation project demonstration。选择 HTTPS、域名根目录、WordPress 7.0.2、English；未点击安装。HTTPS 证书尚未验证。
- 等待用户在安装页亲自设置管理员账号、密码和邮箱并完成提交。代填旧 Gmail 邮箱被安全审核阻止，未绕过。不得将默认生成的临时密码写入项目文档。
- 本地 wp-content 初步扫描未发现 PHP/HTML 超过 1,000,000 字节或其他文件超过 10,000,000 字节；这不是完整兼容性证明。
- 下一步：核验新站安装与 HTTPS；制作独立脱敏迁移副本，迁移页面、主题、图片及必要插件，保留新站管理员，不携带旧询盘、旧管理员会话、旧 SMTP/Turnstile 密钥、Cloudways Redis 和 Studio SQLite；随后测试页面、后台与表单，邮件另行配置验证。
- 正式域名 DNS、Cloudflare、原始归档和本地展示站均未修改。GitHub 不在本次主机迁移操作范围内。

注意：有公网域名/安装空白 WordPress 不等于原网站已迁移，也不等于学校所有核心功能达标。

## 2026-09-12 续作

- 用户明确要求先完成网站上线，沿用上述免费主机迁移范围。
- 当前浏览器工具只提供 Codex 内置浏览器，未连接原 Edge 登录会话；打开 InfinityFree 控制台超时。免费站点 HTTPS 命令行检查也未获得有效 HTTP 响应，不能据此断言服务器宕机或安装完成。
- 已在忽略目录 `.codex-tmp/infinityfree-2026-09-12/wp-content/` 准备文件副本：Hever/Varia、Fluent Forms、Smart Slider 3、开发版自定义 MU 插件和 2026 媒体，共 2,738 文件、69,542,999 字节。
- 此副本不包含 WordPress 配置、数据库、旧询盘、Studio SQLite、Cloudways 缓存插件或旧邮件插件。扩展名筛查命中的 `cacert.pem` 位于 Smart Slider 的证书信任库路径；尚未完成全面内容审查，文件副本不是已验收迁移包。
- 尚未上传任何文件，未修改远程网站。待连接已登录主机面板后核验安装、保留新站管理员、准备脱敏内容与设置导入，再执行上传及 HTTPS/页面/后台/询盘验收。完整旧 SQL 不可直接导入公网展示站。

## 2026-09-13 续作

- 用户明确收窄本次范围：只把已有网站恢复到免费主机；老师验收口径、新增功能、内容补充和其他工作以后再做。
- 已重新连接 Edge 的 Softaculous 会话。安装记录确认 WordPress 7.0.2 于 2026-09-11 15:06 UTC 安装在 `https://chinaworthseeing.wuaze.com/`。
- 已通过安装器的管理入口进入 HTTPS WordPress Dashboard；当前站名为 My Blog、主题 Twenty Twenty-Five，显示 1 个已发布页面和 1 篇默认文章。说明新 WordPress 已安装，但原网站尚未恢复。
- 在忽略目录 `.codex-tmp/infinityfree-2026-09-13/` 准备 12 个校验文件包和临时管理员迁移工具；内容来源是现有独立本地副本，不修改原始备份。共 2,738 个站点文件；导出 71 条公开页面/媒体/菜单/样式记录，其中包括原有 23 个已发布页面，并导出原首页轮播、主题设置与正式询盘表单配置。
- 数据导出采用白名单，不含用户、会话、旧询盘、评论、SMTP/Turnstile 凭据或 Studio/Cloudways 专用配置；过滤旧轮播缓存。展示副本的询盘邮件通知暂不启用，旧 Turnstile 字段不带入，以免依赖已失效配置。邮件与新验证码配置留待后续，不声称已经可用于正式接单。
- 临时工具只提供现有管理员可访问的管理页，要求权限与 WordPress nonce；仅接受固定 SHA-256 清单内的文件包，拒绝未知文件路径和覆盖不同的现有文件；导入前保存空站受影响内容/设置的数据库快照，保留新管理员。迁移工具 PHP 已通过本地 Studio 加载检查，尚未进行远端执行验证。
- 上传并安装该临时插件被自动审批拒绝，原因是公网安装自定义代码需要用户明确确认。未绕过；本次尚未上传迁移文件、未执行远程导入。下一步需用户明确允许安装并使用该临时工具，完成后停用，再继续文件与内容恢复以及页面验证。
- 用户随后明确授权安装并使用临时迁移插件及上传网站。该授权已获得，无需重复询问。执行文件选择上传时，Edge 扩展返回 `Not allowed` / `fileChooser.setFiles failed`；浏览器文档指示需要在 `edge://extensions` 的 ChatGPT 扩展详情启用 `Allow access to file URLs`。当前需要用户完成该扩展权限设置，尚未上传或安装插件，未执行远程导入。

## 2026-09-16 完成记录

- 用户已启用 Edge 扩展的文件 URL 访问权限，并再次明确要求上传网站。
- 通过 Softaculous 管理入口进入新站后台，上传并启用临时迁移工具；12 个文件包全部通过固定 SHA-256 清单校验并完成导入，共恢复 2,738 个站点文件。
- 已恢复 23 个已发布页面、Hever 主题、主导航、首页 Smart Slider 轮播、媒体文件、主题设置及 Fluent Forms 询盘表单；Fluent Forms 与 Smart Slider 3 已启用。
- 迁移保留新站管理员，没有导入旧用户、旧询盘、旧会话、评论、SMTP/Turnstile 凭据或 Studio/Cloudways 专用配置。原运营归档和本地站未修改。
- 已通过公开 HTTPS 页面复核首页、Contact、About、Explore China、Privacy Policy 和 Beijing；首页轮播、导航、主要版块、图片和 Contact 表单均可见。
- 恢复完成后已停用 `CWS Temporary Restore` 临时迁移插件；插件文件暂时保留为未启用状态，没有删除。
- 网站当前可通过 `https://chinaworthseeing.wuaze.com/` 公开访问。询盘邮件通知和 Turnstile 未配置，也未提交真实询盘，因此不得把当前状态表述为邮件收件链路或正式接单流程已通过。
