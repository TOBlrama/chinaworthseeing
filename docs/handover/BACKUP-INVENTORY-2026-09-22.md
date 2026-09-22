# ChinaWorthSeeing 换机备份清单

核验日期：2026-09-22

## 已完成位置

- GitHub Private：`https://github.com/TOBlrama/chinaworthseeing`
- U 盘：`F:\think book备份\ChinaWorthSeeing-2026-09-22`
- GitHub 默认分支：`main`
- GitHub 初始完整提交：`665b2878a0395bbbedb1354506e4ae619a501e34`

## GitHub Private 安全副本

仓库目录为 `graduation-source/`，计划上传以下内容：

- WordPress 自定义 MU 插件及配套 CSS、JavaScript。
- 页面、表单、首页和生产检查脚本。
- 项目需求、决策日志、路线图、评审记录和设计图。
- 首页和城市图片的优化版，以及 `D:\ChinaWorthSeeing.com\pictures` 中的原始素材。
- 2026-09-16 已实际用于 InfinityFree 的脱敏恢复包：12 个文件包和一个临时恢复插件包。

GitHub 版明确排除：

- `.env`：本次扫描未发现。
- `wp-config.php`：本地站和 D 盘旧站均存在，含 WordPress salts/本地配置，不上传。
- 数据库：存在一个原始 SQL 和 SQLite 本地数据库，不上传。
- API Key/SMTP/Turnstile：源码和可上传资料中没有发现实际密钥赋值；原始 SQL 中存在相关旧配置记录，因此只进入离线敏感备份。
- 旧用户、旧询盘、会话、评论和完整 Cloudways 运营归档。
- 私钥：可上传范围未发现私钥。Smart Slider 中的 `.pem` 是公开 CA 证书库，不是项目私钥。

## 离线敏感副本

U 盘需要包含两套目录：

1. 当前项目工作区 `C:\Users\盛义翔\Documents\ChatGPT\入境游网站建设`。
2. 原始站点与原图 `D:\ChinaWorthSeeing.com`。

关键恢复资料：

| 项目 | 本机位置 | 说明 |
|---|---|---|
| Cloudways 完整归档 | `backups/cloudways-cancel-2026-08-20/chinaworthseeing-cloudways-2026-08-20.tgz` | 含完整 `wp-content` 和原始 SQL；包含潜在个人数据与旧配置 |
| Cloudways SQL | `local-demo/import-source/yprwhwzggk-20260820-0854.sql` | 含用户、Fluent Forms 提交、邮件与 Turnstile 旧配置 |
| 本地演示数据库 | `local-demo/site/wp-content/database/.ht.sqlite` | WordPress Studio 本地副本，不进入 GitHub |
| D 盘旧站数据库 | `D:\ChinaWorthSeeing.com\wp-content\database\.ht.sqlite` | 旧站本地数据库，不进入 GitHub |
| D 盘原始图片 | `D:\ChinaWorthSeeing.com\pictures` | 32 个文件，约 120 MB；GitHub 私有仓库另存一份素材副本 |

Cloudways 完整归档已再次核验：

- 大小：74,163,488 bytes
- SHA-256：`69C5F4998670E71727626E308DFB2C8A2B544E3F35E057865BA67CBD3F862F35`

## 安全提醒

离线副本不能直接推送到 GitHub，也不要通过公共网盘分享。换机后不要原样复用旧 SMTP/API Key；先在对应服务商处确认是否仍有效，优先重新生成并撤销旧凭据。

浏览器保存的 GitHub、InfinityFree、WordPress、Cloudflare/域名和邮箱登录状态不属于项目文件。换机前应确认这些账号已经进入自己的密码管理器，并确认两步验证恢复方式可用。

## U 盘复制验证

首次完整复制后核验结果：

- 工作区：源和 U 盘均为 16,937 个文件、1,058,636,335 bytes。
- D 盘旧站：源和 U 盘均为 7,211 个文件、292,609,478 bytes。
- Cloudways 完整归档、Cloudways SQL、本地 SQLite、D 盘 SQLite 和离线 Git bundle 的源/目标 SHA-256 均一致。
- U 盘中的脱敏恢复包与原始图片 SHA-256 清单通过。
- U 盘中的 Git bundle 记录完整提交历史，并可在 GitHub 不可用时恢复。
