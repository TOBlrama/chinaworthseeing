# ChinaWorthSeeing 正式上线执行单｜2026-08-16

状态：核心生产部署已完成；生产询盘邮件全链路测试待单独确认后执行

## 决策状态

- 已确认：项目负责人于 2026-08-16 明确要求正式上线网站。
- 已确认：本次按“英语版可转化 MVP”上线；允许首页、About、Contact、Explore China 与 Privacy Policy 被搜索引擎收录。
- 已确认：仍标记为 `placeholder` 的目的地、体验和实用指南页继续可访问，但单页保持 `noindex`，直到正式内容完成；Explore China 入口页允许收录。
- 已完成：生产后台登录；Cloudways 文件与数据库按需备份；Cloudflare SSL 模式、源站证书和邮件路由只读核验。
- 已完成：脚本部署、Privacy/About/语言/占位提示/索引修复、服务器端与公网复查，以及三把 Cloudways SSH 公钥、本地密钥和服务器临时脚本清理。
- 待完成：获得测试数据及单独确认后，提交一条合成询盘，验证成功状态、后台记录、Gmail 通知与 Reply-To；该项未完成前不得声称生产邮件全链路已通过。

## 变更前线上证据

- `https://www.chinaworthseeing.com/` 可访问，HTTP、裸域名和 `www` 最终统一到 HTTPS `www` 域名。
- 正式域名当前仍输出 `noindex, nofollow`。
- HTML 语言当前为 `zh-Hans`，与首发英文内容不符。
- `/privacy-policy/` 当前返回 WordPress 中文 404。
- `/about/` 当前只有标题，没有正式正文。
- 目的地、Experience 与 Guide 子页当前仍显示内部验收语气的 placeholder 文案。
- Contact 表单、必填错误状态和 Cloudflare Turnstile 容器已加载；尚未完成真实生产提交与 Gmail 收件、Reply-To 验证。
- DNS 已观察到 Cloudflare 代理地址、Cloudflare Email Routing MX、Cloudflare 与 Elastic Email SPF、Elastic Email DKIM、DMARC `p=none`。
- Cloudways 应用备份已于 2026-08-16 11:58:08 UTC（19:58:08 CST）完成，界面提供完整应用恢复入口。
- Cloudflare 当前为 `Full (strict)`；Cloudways Let’s Encrypt 源站证书覆盖 `www.chinaworthseeing.com` 与 `chinaworthseeing.com`，到期日为 2026-11-11，自动续期已开启。
- Cloudflare Email Routing 已启用；`website@chinaworthseeing.com` 转发至已验证的 `felixsheng27@gmail.com`。另观察到一个指向 `cloud-mail` 的活跃 catch-all，当前不纳入本次上线变更。
- Cloudways Master Credentials 仍列出两把 2026-08-13 Codex 临时 SSH 公钥；对应本地私钥已删除，远程公钥仍需在部署前清理。

## 实际执行结果

- 2026-08-16 已运行 `apply-contact-enquiry.php`，恢复并发布 `/privacy-policy/`，将其设为 WordPress 官方隐私政策页，并保持 Contact 表单、Gmail 通知目标和 Turnstile 配置有效。
- 已部署 `cws-production-indexing.php`；Home、About、Contact、Privacy Policy 与 Explore China 不再输出 `noindex`，15 个 placeholder 子页继续由页面级规则输出 `noindex`。
- 已补全 About 正文，将 15 个内部验收占位提示替换为面向访客的公开说明；脚本复跑时没有重复更新。
- WordPress 默认语言已改为 `en_US`。WordPress 以空 `WPLANG` 选项表示默认美式英文，公网 HTML 输出 `lang="en-US"`。
- `blog_public` 已由 `0` 改为 `1`；服务器端验证脚本 13 项全部通过。
- 公网回读确认 Home、About、Contact、Privacy Policy、Explore China 均可访问并允许收录；代表性 Beijing placeholder 页面输出 `noindex`；`/wp-sitemap.xml` 返回 200 与有效 sitemap index。
- Contact 页面仍包含必填行程字段、WhatsApp 国家区号、隐私链接和提交按钮；Turnstile 密钥与表单字段已由服务器端验证，尚未提交生产合成询盘。
- 20:37 CST 左右的人工生产测试已成功保存为 Fluent Forms submission `#4`，但通知邮件失败；WP Mail SMTP 错误日志明确记录 Elastic Email `APIKey Expired`，证明故障发生在提交保存后的发信阶段。
- 21:00 CST 前已按 Cloudways 服务器级 Elastic Email 配置，将 WP Mail SMTP 从失效的 Elastic Email API 连接切换为 Default/PHP mailer。设置保存成功，随后插件测试邮件未再产生失败提示，原 `APIKey Expired` 状态与工具栏错误标记已消失。
- 自动化合成询盘被 Cloudflare Turnstile 正常拦截，没有创建新记录；Fluent Forms Lite 的现有记录重发通知功能属于 Pro 版，本次不为重发购买插件。Gmail 实际收件及修复后的人工表单再提交仍待项目负责人确认。
- 服务器临时部署目录已删除；Cloudways SSH Public Keys 列表已清空，两把 2026-08-13 旧公钥和本次 2026-08-16 临时公钥均已删除；本地两组临时目录已删除且不可从原临时位置恢复。

## 计划变更

1. 使用 2026-08-16 11:58:08 UTC 的 Cloudways 应用文件与数据库备份作为本次回滚点。
2. 删除两把 2026-08-13 Codex 临时 SSH 公钥；添加一把本次部署专用临时公钥，完成部署后立即删除。
3. 向生产 `wp-content/mu-plugins/` 部署 `wordpress/mu-plugins/cws-production-indexing.php`。
4. 运行 `scripts/wordpress/apply-contact-enquiry.php`：恢复 Privacy Policy，更新实际服务商说明，并保持 Contact 表单为已确认版本。
5. 运行 `scripts/wordpress/apply-production-launch.php`：
   - 将 WordPress 站点语言设为 `en_US`；
   - 补充 About 正文；
   - 把内部 placeholder 提示改为面向游客的诚实说明；
   - 确认 Privacy、Contact、Turnstile 与正式域名通过前置条件；
   - 最后一步才解除全站 `noindex`。
6. 运行 `scripts/wordpress/validate-production-launch.php`。
7. 复核 Cloudflare `Full (strict)`、Universal SSL/边缘证书、DNS 和 Email Routing 在变更后没有意外变化。
8. 进行桌面与手机前台检查，并在用户确认测试数据后提交一条合成询盘，验证后台记录、成功状态、Gmail 通知与 Reply-To。

## 文件影响

- 本地新增：`wordpress/mu-plugins/cws-production-indexing.php`
- 本地新增：`scripts/wordpress/apply-production-launch.php`
- 本地新增：`scripts/wordpress/validate-production-launch.php`
- 本地更新：`scripts/wordpress/apply-contact-enquiry.php`
- 生产新增：一个可移植 MU Plugin；不修改 Hever 父主题，不引入新付费插件或封闭页面构建器。
- 生产数据库更新：About、Privacy、placeholder 文案、WordPress 语言、站点描述、隐私页设置和搜索可见性。
- 本次默认不新增或改写 Cloudflare DNS 记录；只有核验发现正式域名配置错误时才另行说明影响并处理。

## 回滚

- 首选：使用本次变更前的 Cloudways 应用备份恢复文件和数据库。
- 快速收录回滚：立即把 WordPress 搜索可见性恢复为关闭，使站点重新输出 `noindex, nofollow`。
- 代码回滚：移除本次新增的 indexing MU Plugin，并恢复变更前文件副本。
- DNS 默认不变，因此本次不预设 DNS 回滚；若后续确实修改 DNS，必须先记录原值和 TTL。

## 完成条件

- Home、About、Contact、Privacy Policy 和 Explore China 返回 200，英文 `lang` 正确。
- Home 等正式页面不再输出 `noindex`；placeholder/structure 页面仍输出 `noindex`。
- Privacy Policy 不再 404，Contact 隐私链接正确。
- Turnstile、表单验证、成功状态、后台记录、Gmail 通知和 Reply-To 全链路通过。
- Cloudflare `Full (strict)`、证书、备份与回滚路径已核验。
- 桌面和手机无阻断性错误、整页横向溢出或关键交互失效。
- 决策日志记录实际完成结果；未完成项不得写成已完成。
