# ChinaWorthSeeing 新电脑继续工作指南

## 1. 先恢复 GitHub 工作副本

1. 在新电脑安装 Git、PHP/WordPress 本地环境和常用编辑器。
2. 登录 GitHub，克隆私有仓库。
3. 阅读 `README.md`、`project/AGENTS.md`、`docs/project-sources/01-master-project-overview.md`、`docs/requirements/phase-1-mvp.md` 和 `docs/decision-log.md`。
4. 运行 `scripts/verify-recovery.ps1`，确认脱敏恢复包和图片素材的 SHA-256 完整。

## 2. 继续毕业设计开发

- 自定义代码位于 `wordpress/mu-plugins/`。
- 页面和配置脚本位于 `scripts/wordpress/`。
- 设计稿、需求、决策和评审记录位于 `docs/`。
- 图片素材位于 `assets/` 与 `media/originals/`。
- GitHub Pages 不能运行 PHP/MySQL，也不能代替 WordPress 主机。

## 3. 重建当前脱敏展示站

`recovery/sanitized-site/packages/` 是 2026-09-16 已在 InfinityFree 实际恢复成功的包：

1. 新建一个空白 WordPress，先自行创建新的管理员账号。
2. 上传并启用 `cws-restore.zip`。
3. 在 **Tools → CWS Restore** 中依次上传 `cws-files-01.zip` 到 `cws-files-12.zip`。
4. 点击启用依赖，再执行公开内容恢复。
5. 验证首页、Contact、About、Explore China、Privacy Policy 和代表性目的地页。
6. 完成后停用并删除临时恢复插件。

这个恢复包保留新管理员，不带入旧用户、旧询盘、SMTP/Turnstile 凭据或旧会话。邮件通知和验证码需要重新配置和测试。

## 4. 恢复未来正式运营版本

只有 U 盘离线副本包含完整 Cloudways 归档、SQL、SQLite 和旧站配置。正式恢复流程为：

1. 创建标准 WordPress 主机和新的数据库账号。
2. 从完整归档恢复文件和数据库。
3. 替换数据库凭据、站点 URL 和所有旧密钥。
4. 配置 SSL、DNS、事务邮件、SPF/DKIM、Turnstile、备份和安全策略。
5. 完成移动端、表单入库、实际收件、Reply-To、隐私和回滚测试后再投入运营。

不要把页面能打开当成正式运营链路已经完成。

## 5. 必须保留的账号访问

- GitHub 私有仓库。
- InfinityFree 账户与 WordPress 管理员。
- 正式域名与 Cloudflare/DNS。
- 业务邮箱、SMTP/邮件服务和两步验证恢复方式。

密码、验证码和恢复码应保存在个人密码管理器中，不写入仓库或项目文档。
