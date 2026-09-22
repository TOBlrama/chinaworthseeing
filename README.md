# ChinaWorthSeeing — Private Project Repository

这是 ChinaWorthSeeing 的私有工作仓库，用于换机交接、毕业设计开发和以后正式运营迭代。当前公开展示站为 `https://chinaworthseeing.wuaze.com/`。

## 当前功能

- 旅游内容导航与展示：目的地、体验、旅行指南；部分详情页仍为占位内容。
- 旅行需求询盘：Fluent Forms 表单，旅行信息与联系方式、国家区号选择、隐私说明。
- WordPress 后台：内容、媒体、用户及权限管理；询盘管理依赖 Fluent Forms。
- 自定义 MU 插件提供导航、轮播交互、表单表现与索引控制。

未完成验收：第三项独立核心业务功能、完整内容、演示环境的登录及提交回归测试、真实邮件收件、毕业设计全部文档和视频。未实现游客会员中心；不能把管理员登录等同于游客会员系统。

## 源码结构与依赖

- `wordpress/mu-plugins/`：项目自定义 PHP、CSS、JavaScript 和区号数据。
- `scripts/wordpress/`：页面/表单配置与只读验证脚本。
- 依赖：WordPress、Hever/Varia 主题、Fluent Forms、Smart Slider 3；邮件服务须单独配置。
- 生产环境使用 PHP 与 MySQL/MariaDB；WordPress Studio 本地环境使用 SQLite 集成。

仓库包含自定义源码、项目文档、设计材料、原始图片和一套已经实际验证过的脱敏恢复包。使用 `recovery/sanitized-site/` 可以在新的空白 WordPress 上恢复当前公开页面、媒体、主题、导航、首页轮播和询盘表单结构。第三方主题和插件仍归各自作者所有，不能宣称为本人原创。

换机步骤见 `docs/handover/NEW-COMPUTER-SETUP.md`，备份与敏感数据边界见 `docs/handover/BACKUP-INVENTORY-2026-09-22.md`。

## 部署边界

GitHub 是代码托管，不是这个 WordPress 网站的 PHP 服务器。GitHub Pages 不运行 PHP，不能承载真实 WordPress 后台及数据库；不要直接把 PHP 文件发布为 Pages 网站。

学校允许本地部署。完整运营数据库、旧询盘、账号配置、`wp-config.php` 和 Cloudways 完整归档只保存在离线 U 盘，不进入 GitHub。脱敏恢复包不含旧用户、旧询盘或凭据。

已有 WordPress 环境时可把 `wordpress/mu-plugins/` 的项目文件复制到站点对应目录。`apply-*.php` 会修改内容/设置，只能在备份完成、依赖和脚本所需资源已准备好后有选择地执行，禁止对生产站盲目批量运行。`validate-*.php` 用于只读检查。Studio 站点通过 `studio wp eval-file <脚本绝对路径>` 执行。

## 安全与后续版本

不提交后台密码、SMTP/API Key、生产数据库、询盘个人信息或完整运营备份。数据使用脱敏展示副本。可持续提交开发中版本，完成后再创建毕设定稿标签和独立完整备份；后续运营从定稿继续开发，不覆盖定稿。

学校是否认可基于 CMS/插件的开发深度及管理员用户系统，需由指导老师确认；本 README 不代表达标证明。
