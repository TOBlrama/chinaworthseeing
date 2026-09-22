# 第一阶段 MVP 需求

版本：v0.1  
状态：从项目总说明书提炼，待项目负责人确认  
来源：01｜项目总说明书 v0.1

## 1. 目标

2026-09-10 当前阶段补充（D-029/D-030）：网站用于毕业设计，学校允许在线或本地运行，要求用户系统、数据管理和至少三项核心业务功能。项目负责人仍希望免费在线展示、真实后台登录、询盘入库和邮件发送，并使用 GitHub。独立本地副本已恢复基础页面，完整业务验收尚未通过；GitHub 源码已整理但未上传。GitHub Pages 单独无法运行 WordPress，公网运行环境与邮件服务待确认。第三项业务与游客用户系统是否新增不能擅自写死。毕设定稿需保存固定版本及完整备份，供后续改进并迁回 Cloudways。下文为原业务 MVP 基线；Cloudways 在线状态已被 D-028 暂停托管决定取代。

MVP 的目标不是建设完整 OTA，而是验证：

海外游客是否会因为网站提供的中国旅行内容与服务说明产生信任，提交有效旅行需求，并愿意通过 WhatsApp 继续沟通直至形成交易机会。

首要业务指标是高质量询盘，而不是页面数量、功能数量或单纯流量。

## 2. 目标用户

主要用户是准备前往中国旅行的海外游客，尤其包括：

- 第一次来中国的游客
- 不熟悉中国城市、路线和旅行方式的游客
- 对支付、交通、网络、订票、预约与酒店入住感到困惑的游客
- 需要个性化路线建议或人工协助的游客

所有内容和流程应按“一个外国游客第一次来中国会遇到什么问题”来检查。

## 3. 已确认的核心转化路径

1. 用户通过搜索、内容、社交媒体、品牌曝光或外部平台进入网站
2. 用户获得有用的中国旅行信息
3. 用户理解网站可以提供的帮助并建立信任
4. 用户提交基础旅行需求和 WhatsApp
5. 业务负责人主动联系
6. 双方在 WhatsApp 中深入确认需求
7. 提供方案或旅行服务并推动成交

第一阶段不要求网站独立完成整个交易闭环。

### 3.1 已确认的第一版主导航

第一版全站主导航依次为：

1. Home
2. Explore China
3. About
4. Contact
5. Start Planning

Home 使用静态页面作为网站首页。主导航中的 Explore China 只显示为不可点击的文本标签，由旁边的独立展开按钮控制菜单；`/explore-china/` Hub 页面本身继续保留。桌面端提供 Places to Go、Experiences、Plan Your Trip 三组宽幅 Mega Menu，这三个栏目标题同样只显示为不可点击文本；移动端改为逐层 accordion，由标题旁的独立按钮展开。目的地页面使用 `/destinations/[slug]/`；Experience 页面使用 `/experiences/[slug]/`；实用指南页面使用 `/plan-your-trip/[slug]/`。导航数据继续使用 WordPress 原生页面与菜单系统维护。

本次只确认上述信息架构、入口与交互；不自动确认各目的地、Experience 或 Guide 的正式长文内容，也不改变已确认的首页 Hero、Brand Statement 和 Why Travel 视觉设计。

全站不使用 Hever/Varia 默认的 Footer 小工具内容，不显示搜索、Recent Posts、Recent Comments、Archives、Categories、Hello world 或默认空 Banner。普通页面底部继续保留站点名称和 Privacy Policy；Contact 页面仍使用其独立的无主题页脚展示方式。

### 3.2 已确认的首页 What We Do

首页 Hero 后、Brand Statement 前显示极简 `What We Do` 区块，用于明确说明 ChinaWorthSeeing 提供详细、个性化的旅行计划，而不是打包旅行团。

主标题：`We create detailed, personalized travel plans — without selling you a packaged tour.`

副标题：`Most bookings can be made through links included in your itinerary, or you're always free to book everything yourself.`

当前服务边界为：提供定制旅行计划和旅行咨询，不代客户预订酒店、火车票、门票或其他旅游产品；行程可以包含购买链接，客户也可自由选择其他渠道购买。区块只使用上述两层文字，不显示眉题、价值点、卡片、图标、图片、CTA 或额外说明模块。背景与导航栏同为暖米色；两层文字使用与 Brand Statement 标题一致的无衬线字体族，仍保留主副标题字号层级。

### 3.3 已确认的首页 Brand Statement

首页 Hero 后紧接居中 Brand Statement 区块。

标题：

`China should feel easier to explore`

正文：

> China can be exciting, beautiful and deeply rewarding — but for many international travelers, planning a trip here can still feel more complicated than it should.
>
> We created ChinaWorthSeeing to make that journey simpler.
>
> To help you understand where to go, how to travel, and what is genuinely worth your time — while taking you beyond the usual checklists to experience a more authentic side of China.
>
> Not just the places everyone tells you to see, but the landscapes, cultures, food and moments that make China truly worth seeing.

四段正文下方显示 `GET IN TOUCH` 按钮，按钮仍属于 Brand Statement 区块并链接到 `/contact/`。桌面端使用内容宽度按钮，移动端扩展到 Brand Statement 文本列宽度，触控高度不小于 48px。Brand Statement 背景为纯白色。

该区块只确认上述文案、CTA 和居中窄文本列版式，不自动确认其他品牌承诺、服务范围或首页后续区块。Hero 黑色底条与该区块之间不设置额外 section 间隙；保留正常内容留白，但顶部 padding 不应形成过大的视觉断层。

### 3.4 已确认的首页 Why Travel 区块

Brand Statement 后紧接三卡图文区块。

眉题：

`A LAND OF STORIES, SCENERY & FLAVOUR`

主标题：

`Why travel with ChinaWorthSeeing`

卡片内容按以下顺序展示：

1. `Natural Wonders` — Discover the landscapes that make China unlike anywhere else.
2. `History & Heritage` — Step into thousands of years of history, from imperial landmarks to ancient streets and living traditions.
3. `Flavours of China` — Taste China beyond the tourist checklist, from regional classics to the places locals actually love.

版式采用居中眉题与主标题、上图下文卡片；桌面端三列，手机端单列，三张图片按项目负责人提供的 `1`、`2`、`3` 顺序分别对应三张卡片。眉题到主标题与主标题到图片卡片使用相同垂直间距。三个标题分别链接到 `/experiences/natural-wonders/`、`/experiences/history-heritage/`、`/experiences/flavours-of-china/`，并与 Explore China 导航中的 Experiences 入口一致。该区块只确认上述文案、图片顺序、版式和链接，不自动确认其他服务承诺或后续首页结构。

### 3.5 已确认的首页城市 Destination Carousel

Why Travel 区块之后新增横向城市目的地轮播。

眉题：

`PLACES WORTH DISCOVERING`

主标题：

`Explore China, City by City`

第一版城市及短文案：

1. `BEIJING` — Where imperial China still feels alive.
2. `SHANGHAI` — China at its most modern, stylish and energetic.
3. `XI'AN` — The gateway to China’s ancient past.
4. `GUILIN` — China’s most iconic landscapes.
5. `HANGZHOU` — Lakeside beauty, tea and a slower rhythm.
6. `SUZHOU` — Classical gardens, canals and Jiangnan elegance.
7. `GUANGZHOU` — The heart of Cantonese food and southern China.

每张卡片只包含 3:4 城市图片、英文城市名、一句短定位和 `Discover [City] →`。桌面约显示 3.2–3.4 张、平板约 2.1–2.4 张、手机约 1.1–1.2 张；下一张卡片保留部分露出。不显示左右按钮；桌面可在卡片图片或文字区域按住鼠标直接拖动并支持键盘方向键，移动端直接横向滑动；不得形成整页横向溢出。

七个城市 Destination 页面已按 `/destinations/[slug]/` 建立。每张卡片的图片、城市名称和 `Discover [City]` 均直接进入同一城市页面，并与 Mega Menu 的 Places to Go 链接一致；不增加 Cities 中间层。城市页面当前只包含诚实的内容准备状态，正式页面内容仍待后续确认。该区块只确认上述内容、顺序、版式、交互和链接，不自动确认城市详情页正文或首页后续结构。

## 4. 已确认的内容范围

### 4.1 旅游信息

- 城市介绍
- 景点、美食和酒店相关信息
- 城市与城际交通
- 支付、网络、手机卡或 eSIM、地图
- 高铁、地铁、打车、机场交通
- 景区预约、外国护照购票、酒店入住
- 第一次来中国注意事项

### 4.2 路线与旅行规划

- 不同旅行天数的安排
- 城市组合与先后顺序
- 城市之间的交通方式
- 不同人群适合的路线
- 第一次来中国的规划方法
- 定制行程相关说明

### 4.3 人工服务

行程规划、咨询及其他旅行协助属于发展方向。第一阶段正式销售哪些服务、价格与履约方式仍待确认，公开页面不得先行承诺。

## 5. 首批城市

- Beijing 北京
- Shanghai 上海
- Guangzhou 广州
- Suzhou 苏州
- Xi'an 西安
- Guilin 桂林
- Hangzhou 杭州

城市清单是首批资料与内容范围，不是未来业务的永久边界。

## 6. 询盘功能

### 6.1 最低字段

- First name、Last name
- Email address
- WhatsApp：国家区号和手机号码
- 想去的城市：多选；Beijing、Shanghai、Xi'an、Guilin、Hangzhou、Suzhou、Guangzhou、Chengdu、Chongqing、Not sure yet / I'd like your advice
- 预计旅行月份和年份；允许选择 Not sure yet
- 旅行时长
- 旅行人数
- Additional comments：可选

除 Additional comments 外，其余字段均为必填。第一阶段不收集预算、推荐来源、Newsletter 同意或重复确认邮箱。

### 6.2 体验原则

- 保持低门槛，不要求首次提交几十项信息
- 提交后由人工主动联系
- 复杂需求留到 WhatsApp 中继续确认
- 移动端应作为关键使用场景
- 城市多选的已选标签和删除按钮不得撑大输入框；月份、年份、旅行时长和人数的 `Select...` 仅作初始占位提示，不得作为可提交选项出现在展开菜单中
- WhatsApp 国家区号使用可搜索、可滚动的国家/地区下拉，显示国家、国际区号和国旗；区号与手机号继续作为两个独立字段保存
- Contact 页面不显示主题默认的 Banner、搜索、Recent Posts、Recent Comments、Archives、Categories 或 WordPress 署名区；表单分区标题和字段标签比输入内容高两个像素级，输入值、占位符和选项文字保持原字号
- 表单提交后保存在 WordPress 的 Fluent Forms 后台，并向 `felixsheng27@gmail.com` 发送通知
- 后续沟通可根据客户偏好通过 Email 或 WhatsApp 人工进行
- 提交按钮上方必须显示英文隐私提示；`Privacy Policy` 为粗体、下划线链接并进入 `/privacy-policy/`

### 6.3 已确认的隐私与防垃圾边界

- 运营主体：`ChinaWorthSeeing is operated by Yixiang Sheng, an individual based in China.`
- 公开隐私联系方式只使用 `felixsheng27@gmail.com`，不公开地址
- 不发送营销信息，不启用 Newsletter，不出售、出租或交换个人信息
- 仅在客户明确要求或服务已确认时，向旅行供应商提供完成合作所需的最少资料
- 非活跃或未成交询盘一般保存 3 个月；完成服务一般保存 1 个月；安全日志和备份一般保存 30 天；投诉、争议、欺诈调查或法律义务可例外延长
- 网站面向成年人；初步询盘由成年人提交；未成年人、健康、饮食、同行人员等资料只在明确服务所需时按最少范围收集
- 隐私请求发送至公开邮箱，并力求在 30 天内回复
- 防垃圾方案选择 Cloudflare Turnstile；生产站点密钥和私钥已配置，Contact 表单包含 Turnstile 字段。服务器端配置检查已通过，生产成功提交与失败状态仍需在合成询盘测试中复核

### 6.4 生产运营仍需完成

- 提交一条经单独确认的生产合成询盘，验证 Turnstile、正常提交、失败状态、后台记录、Gmail 收件与 Reply-To；当前已确认配置与 DNS 记录，不代表生产邮件全链路已通过
- 确认普通询盘的业务响应时限；隐私请求的 30 天目标不能自动视为销售询盘响应承诺
- 建立线索状态与定期删除流程；WordPress 当前不能自动区分未成交询盘和已完成服务
- Privacy Policy 已于 2026-08-16 按生产启用的 WordPress/Fluent Forms、Cloudways/DigitalOcean、Cloudflare、Gmail、Elastic Email 与 WhatsApp/Meta 复核；后续服务商或处理流程变化时必须再次更新

## 7. 多语言

必须从技术与内容模型初期支持多语言扩展。

已明确的目标语言：

1. English
2. 繁體中文
3. 한국어
4. 日本語
5. Bahasa Melayu
6. Русский
7. Español

English 是默认主要语言。2026-08-16 正式上线的公开 MVP 仅以 English 输出，HTML 语言为 `en-US`；其他语言的首发范围仍待确认。未发布语言不应以空页面或低质量机器翻译方式上线。

## 8. 平台与系统边界

- 公开网站使用 WordPress
- Notion 仅作为内部资料、研究和业务管理系统
- 网站默认不直接公开或嵌入整个 Notion 数据库
- 网站不得依赖 Notion 才能正常运行
- 网站应可迁移、可维护、可扩展
- 初期主机成本可以较低，但不得以重建网站为服务器升级代价

### 8.1 已确认的 MVP 主机基础

- 主机平台：Cloudways Flexible
- 底层云服务商：DigitalOcean
- 初始规格：2GB RAM、1 vCPU、50GB 存储、2TB 流量
- 机房：New York，以兼顾美国主要访客和欧洲访问
- 域名与 DNS：继续由 Cloudflare 管理；正式域名已在 Cloudflare `Full (strict)` 下运行，本次内容与索引上线未修改 DNS
- 环境要求：使用测试环境完成开发与验证；正式环境启用 HTTPS、自动备份和独立可恢复备份
- 可迁移要求：保留 WordPress 文件、数据库、媒体和配置的导出路径，不把网站写死在 Cloudways 专有功能中

基础价格核验时为 11 美元/月，异地备份、税费、汇率、邮箱和付费附加服务另计。表单使用 Fluent Forms，生产防垃圾使用已配置的 Cloudflare Turnstile；主题、多语言扩展、缓存及其余安全方案仍待确认。当前 SEO 基线已包含正式页面收录、placeholder 页面级 `noindex` 和 WordPress sitemap。

## 9. 第一阶段非目标

除非后续需求明确变化，MVP 不优先建设：

- 复杂会员、积分和大型客户账户中心
- 完整 OTA
- 酒店或机票实时库存
- 大规模在线旅游商品交易
- 复杂自动报价或 AI 行程生成器
- 大型企业 CRM
- 微服务和 Kubernetes

这份清单表示“不在当前优先范围”，不表示未来永久排除。

## 10. 内容质量要求

- 优先回答用户如何做决定，而不是堆积信息
- 说明优点、缺点、适合谁、不适合谁和实际注意事项
- 避免把所有地点写成 Must Visit 或 Best
- 不以网红热度作为主要推荐依据
- 动态信息注明核验或更新时间
- 重要动态信息发布前必须核验

## 11. 非功能要求

### 可维护性

业务负责人不应被迫成为程序员。日常内容更新和询盘查看应有清晰、低风险的后台流程。

### 可迁移性

内容、媒体、表单线索和配置应有导出、备份与恢复路径。

### 性能

页面设计、图片和插件选择应兼顾海外访问速度，具体性能预算在技术方案阶段确定。

### 安全与隐私

需要最小权限、更新机制、备份、反垃圾、表单保护和个人信息处理规则。Contact 表单已发布并链接经生产服务商复核的英文 Privacy Policy，Cloudflare Turnstile 密钥与字段已配置；生产合成询盘邮件全链路和删除运营流程仍需完成。

### SEO

正式页面已启用索引，WordPress sitemap 已恢复，placeholder 子页由可移植 MU Plugin 保持页面级 `noindex`。元数据完善、规范链接复核和未来多语言索引方案仍需继续建设。

## 12. MVP 验收方向

MVP 至少应能证明：

- 海外游客可以理解网站面向谁、能提供什么帮助
- 用户能从内容或目的地页面找到清晰的询盘入口
- 用户能在手机和桌面端顺利提交最低必要信息
- 业务负责人能收到、识别和跟进询盘
- WhatsApp 号码格式可用于实际联系
- 网站内容可以在 WordPress 中维护
- 多语言扩展不需要重建内容模型或网站
- 基础性能、安全、SEO、隐私和备份检查通过
- 可以记录流量、表单开始、表单提交与有效询盘等关键指标

具体数值目标需要在分析与运营方案中另行确认。
