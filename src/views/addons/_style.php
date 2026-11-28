<?php
/**
 * 插件市场页面样式（pm-*）
 *
 * 只用自己的类名，不依赖 Bootstrap 3 / 4 的栅格和组件差异，3.x（AdminLTE 2）和 4.x（AdminLTE 3）显示一致。
 * 授权站后台的「预览详情页」也引用这里。
 *
 * @var $this \yii\web\View
 */
$this->registerCss(<<<'CSS'
.pm { --pm-primary: #2563eb; --pm-text: #1f2329; --pm-sub: #8f959e; --pm-line: #eef0f3; color: var(--pm-text); font-size: 14px; }
.pm *, .pm *:before, .pm *:after { box-sizing: border-box; }
.pm a { text-decoration: none; }
.pm-card-box { background: #fff; border-radius: 10px; box-shadow: 0 1px 3px rgba(15, 23, 42, .06); margin-bottom: 16px; }
.pm-pad { padding: 18px 20px; }
.pm-sub { color: var(--pm-sub); font-size: 12px; }
.pm-mono { font-family: SFMono-Regular, Menlo, Consolas, monospace; font-size: 12px; }
.pm-empty { padding: 60px 20px; text-align: center; color: var(--pm-sub); }
.pm-empty i { font-size: 40px; color: #d0d4da; display: block; margin-bottom: 12px; }
.pm-alert { padding: 10px 14px; border-radius: 8px; margin-bottom: 12px; font-size: 13px; }
.pm-alert.err { background: #fff1ec; color: #b42318; }
.pm-alert.warn { background: #fff7e6; color: #ad6800; }
.pm-alert.info { background: #eef5ff; color: #1d4f9e; }
.pm-alert.ok { background: #e8f7ef; color: #0e7a45; }

/* 顶部导航 */
.pm-nav { display: flex; align-items: center; flex-wrap: wrap; gap: 4px; background: #fff; border-radius: 10px; padding: 6px 10px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(15, 23, 42, .06); }
.pm-nav a.pm-tab { padding: 8px 14px; border-radius: 8px; color: #4e5969; font-size: 14px; position: relative; }
.pm-nav a.pm-tab:hover { background: #f5f7fa; color: var(--pm-text); }
.pm-nav a.pm-tab.on { color: var(--pm-primary); background: #eef4ff; font-weight: 600; }
.pm-nav .pm-upgrade-pill { display: inline-flex; align-items: center; gap: 4px; margin: 0 6px 0 -4px; padding: 2px 9px; border-radius: 999px; background: #fff4e5; color: #c2410c; border: 1px solid #fed7aa; font-size: 12px; line-height: 18px; font-weight: 500; text-decoration: none; white-space: nowrap; }
.pm-nav .pm-upgrade-pill:hover { background: #ffedd5; color: #9a3412; text-decoration: none; }
.pm-upgrade-link { display: inline-block; margin-top: 2px; font-size: 12px; color: #c2410c; white-space: nowrap; }
.pm-upgrade-link:hover { color: #9a3412; }
.pm-nav .pm-badge { display: inline-block; min-width: 18px; padding: 0 5px; margin-left: 4px; border-radius: 9px; background: #ef4444; color: #fff; font-size: 11px; line-height: 18px; text-align: center; font-weight: 500; }
.pm-nav .pm-nav-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
.pm-account { font-size: 13px; color: #4e5969; }
.pm-account b { color: var(--pm-text); }
.pm-account a { margin-left: 6px; }

/* 按钮 */
.pm-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 34px; padding: 0 16px; border-radius: 8px; border: 1px solid #dcdfe6; background: #fff; color: #1f2329 !important; font-size: 13px; cursor: pointer; white-space: nowrap; line-height: 1; transition: background .15s, border-color .15s; }
.pm-btn:hover { border-color: #b8c0cc; background: #f8fafc; }
.pm-btn.primary { background: var(--pm-primary); border-color: var(--pm-primary); color: #fff !important; }
.pm-btn.primary:hover { background: #1d4ed8; border-color: #1d4ed8; }
.pm-btn.warn { background: #f59e0b; border-color: #f59e0b; color: #fff !important; }
.pm-btn.warn:hover { background: #d97706; }
.pm-btn.success { background: #10b981; border-color: #10b981; color: #fff !important; }
.pm-btn.danger { color: #d4380d !important; }
.pm-btn.danger:hover { border-color: #f3b4a2; background: #fff6f3; }
.pm-btn.lg { height: 42px; padding: 0 22px; font-size: 15px; font-weight: 600; }
.pm-btn.sm { height: 28px; padding: 0 10px; font-size: 12px; }
.pm-btn[disabled], .pm-btn.disabled { opacity: .55; cursor: not-allowed; pointer-events: none; }
.pm-link { color: var(--pm-primary) !important; cursor: pointer; }

/* 标签 */
.pm-tag { display: inline-flex; align-items: center; gap: 4px; padding: 0 8px; border-radius: 4px; font-size: 12px; line-height: 22px; background: #f2f3f5; color: #4e5969; white-space: nowrap; }
.pm-tag.blue { background: #eef4ff; color: #1d4ed8; }
.pm-tag.green { background: #e8f7ef; color: #0e7a45; }
.pm-tag.orange { background: #fff4e5; color: #c26a00; }
.pm-tag.red { background: #fff1ec; color: #d4380d; }
.pm-tag.purple { background: #f3edff; color: #6d28d9; }
.pm-price { font-weight: 700; color: #ea580c; font-variant-numeric: tabular-nums; }
.pm-price.free { color: #059669; }

/* 筛选栏 */
.pm-filter { display: flex; flex-wrap: wrap; gap: 10px 18px; align-items: center; padding: 14px 18px; }
.pm-search { position: relative; flex: 1 1 260px; max-width: 360px; }
.pm-search input { width: 100%; height: 36px; border: 1px solid #dcdfe6; border-radius: 8px; padding: 0 12px 0 34px; font-size: 13px; outline: none; }
.pm-search input:focus { border-color: var(--pm-primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, .12); }
.pm-search i { position: absolute; left: 12px; top: 11px; color: #b4b9c1; }
.pm-seg { display: inline-flex; background: #f2f3f5; border-radius: 8px; padding: 3px; }
.pm-seg a { padding: 5px 12px; border-radius: 6px; color: #4e5969; font-size: 13px; white-space: nowrap; }
.pm-seg a.on { background: #fff; color: var(--pm-text); font-weight: 600; box-shadow: 0 1px 2px rgba(0, 0, 0, .08); }
.pm-seg a small { color: var(--pm-sub); margin-left: 3px; }
.pm-select { height: 34px; border: 1px solid #dcdfe6; border-radius: 8px; padding: 0 10px; font-size: 13px; background: #fff; color: var(--pm-text); }
.pm-groups { display: flex; flex-wrap: wrap; gap: 6px; padding: 0 18px 14px; }
.pm-groups a { padding: 4px 12px; border-radius: 14px; background: #f5f7fa; color: #4e5969; font-size: 12px; }
.pm-groups a:hover { background: #eef1f6; }
.pm-groups a.on { background: var(--pm-primary); color: #fff; }
.pm-groups a small { opacity: .7; margin-left: 2px; }
.pm-result { display: flex; align-items: center; justify-content: space-between; margin: 0 2px 10px; font-size: 13px; color: var(--pm-sub); }

/* 插件卡片 */
.pm-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
.pm-card { display: flex; flex-direction: column; background: #fff; border-radius: 12px; border: 1px solid transparent; box-shadow: 0 1px 3px rgba(15, 23, 42, .06); padding: 18px; color: inherit !important; transition: box-shadow .2s, transform .2s, border-color .2s; position: relative; min-height: 210px; }
.pm-card:hover { box-shadow: 0 10px 28px rgba(15, 23, 42, .12); transform: translateY(-2px); border-color: #e3e8f0; }
.pm-card-top { display: flex; gap: 12px; align-items: flex-start; }
.pm-logo { width: 52px; height: 52px; border-radius: 12px; object-fit: cover; flex: 0 0 52px; background: #f2f3f5; }
.pm-card-title { flex: 1; min-width: 0; }
.pm-card-title h4 { margin: 2px 0 4px; font-size: 16px; font-weight: 600; color: var(--pm-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.pm-card-title .pm-sub { display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.pm-card-brief { margin: 12px 0 10px; color: #4e5969; font-size: 13px; line-height: 20px; height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
.pm-card-tags { display: flex; flex-wrap: wrap; gap: 6px; min-height: 22px; }
.pm-card-foot { margin-top: auto; padding-top: 14px; display: flex; align-items: center; justify-content: space-between; border-top: 1px dashed var(--pm-line); margin-top: 14px; font-size: 12px; color: var(--pm-sub); }
.pm-card-foot .pm-price { font-size: 15px; }
.pm-ribbon { position: absolute; top: 14px; right: 14px; }

/* 详情页 */
.pm-dh { display: flex; gap: 22px; align-items: flex-start; padding: 24px; }
.pm-dh .pm-logo { width: 88px; height: 88px; flex-basis: 88px; border-radius: 18px; }
.pm-dh-main { flex: 1; min-width: 0; }
.pm-dh-main h2 { margin: 0 0 6px; font-size: 24px; font-weight: 700; line-height: 1.3; padding: 0; }
.pm-dh-main h2 small { font-size: 13px; color: var(--pm-sub); font-weight: 400; margin-left: 8px; }
.pm-dh-brief { color: #4e5969; font-size: 14px; margin: 0 0 12px; line-height: 1.6; }
.pm-dh-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }
.pm-meta { display: flex; flex-wrap: wrap; gap: 6px 20px; font-size: 13px; color: var(--pm-sub); }
.pm-meta b { color: var(--pm-text); font-weight: 500; margin-left: 4px; }
.pm-dh-side { flex: 0 0 250px; display: flex; flex-direction: column; gap: 10px; align-items: stretch; text-align: center; }
.pm-dh-side .pm-price { font-size: 26px; line-height: 1.2; }
.pm-dh-side .pm-btn { width: 100%; }
.pm-dh-side select { width: 100%; }
.pm-dh-note { font-size: 12px; color: var(--pm-sub); line-height: 1.5; }
.pm-layout { display: flex; gap: 16px; align-items: flex-start; }
.pm-main { flex: 1 1 auto; min-width: 0; }
.pm-aside { flex: 0 0 300px; }
.pm-aside h5 { margin: 0 0 12px; font-size: 14px; font-weight: 600; }
.pm-kv { width: 100%; font-size: 13px; }
.pm-kv td { padding: 6px 0; vertical-align: top; border: 0; }
.pm-kv td:first-child { color: var(--pm-sub); width: 76px; white-space: nowrap; }
.pm-deps { list-style: none; margin: 0; padding: 0; }
.pm-deps li { display: flex; align-items: center; justify-content: space-between; padding: 7px 0; border-bottom: 1px dashed var(--pm-line); font-size: 13px; }
.pm-deps li:last-child { border-bottom: 0; }
.pm-ok { color: #059669; }
.pm-no { color: #d4380d; }

/* 截图 */
.pm-gallery { padding: 16px; }
.pm-shot-main { position: relative; border-radius: 10px; overflow: hidden; background: #f5f7fa; min-height: 180px; display: flex; align-items: center; justify-content: center; cursor: zoom-in; }
.pm-shot-main img { display: block; width: 100%; max-height: 560px; object-fit: contain; }
.pm-shot-nav { position: absolute; top: 50%; transform: translateY(-50%); width: 36px; height: 36px; border-radius: 50%; background: rgba(15, 23, 42, .45); color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 0; }
.pm-shot-nav.prev { left: 12px; } .pm-shot-nav.next { right: 12px; }
.pm-thumbs { display: flex; gap: 8px; margin-top: 10px; overflow-x: auto; }
.pm-thumbs img { width: 96px; height: 60px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 2px solid transparent; opacity: .7; flex: 0 0 96px; }
.pm-thumbs img.on { border-color: var(--pm-primary); opacity: 1; }
.pm-lightbox { position: fixed; inset: 0; z-index: 3000; background: rgba(0, 0, 0, .85); display: none; align-items: center; justify-content: center; cursor: zoom-out; }
.pm-lightbox img { max-width: 92vw; max-height: 90vh; border-radius: 6px; }

/* 详情选项卡 */
.pm-tabs-in { display: flex; gap: 4px; border-bottom: 1px solid var(--pm-line); padding: 0 16px; overflow-x: auto; }
.pm-tabs-in a { padding: 14px 14px 12px; color: #4e5969; border-bottom: 2px solid transparent; margin-bottom: -1px; white-space: nowrap; font-size: 14px; }
.pm-tabs-in a.on { color: var(--pm-primary); border-bottom-color: var(--pm-primary); font-weight: 600; }
.pm-tabs-in a small { color: var(--pm-sub); margin-left: 2px; font-weight: 400; }
.pm-pane { display: none; padding: 22px 24px; }
.pm-pane.on { display: block; }

/* 详细介绍内容（授权站写的 HTML） */
.pm-intro { line-height: 1.8; font-size: 14px; color: #333; overflow-wrap: anywhere; }
.pm-intro img { max-width: 100% !important; height: auto !important; }
.pm-intro h3 { font-size: 17px; font-weight: 700; margin: 26px 0 12px; padding-left: 10px; border-left: 3px solid var(--pm-primary); line-height: 1.3; }
.pm-intro h3:first-child, .pm-intro .pm-hero + h3 { margin-top: 6px; }
.pm-intro .pm-hero { margin: 0 0 18px; }
.pm-intro .pm-hero img { border-radius: 12px; width: 100%; }
.pm-intro .pm-hl { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin: 0 0 8px; }
.pm-intro .pm-hl-item { border: 1px solid var(--pm-line); border-radius: 10px; padding: 14px 16px; background: linear-gradient(180deg, #f8faff, #fff); }
.pm-intro .pm-hl-item h4 { margin: 0 0 6px; font-size: 15px; font-weight: 600; color: #1d4ed8; }
.pm-intro .pm-hl-item p { margin: 0; color: #4e5969; font-size: 13px; line-height: 1.6; }
.pm-intro ul.pm-feats { list-style: none; padding: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 8px 18px; }
.pm-intro ul.pm-feats li { position: relative; padding-left: 22px; }
.pm-intro ul.pm-feats li:before { content: ""; position: absolute; left: 2px; top: 7px; width: 12px; height: 12px; border-radius: 50%; background: #dbeafe; box-shadow: inset 0 0 0 3px #2563eb; }
.pm-intro ul.pm-scenes { list-style: none; padding: 0; margin: 0 0 8px; display: flex; flex-wrap: wrap; gap: 8px; }
.pm-intro ul.pm-scenes li, .pm-intro .pm-scenes span { display: inline-block; padding: 4px 12px; border-radius: 14px; background: #f3edff; color: #6d28d9; font-size: 13px; line-height: 1.6; }
.pm-intro .pm-flow img { max-width: 980px !important; width: 100%; }
.pm-intro li > p { margin: 0; }
.pm-intro pre { background: #0f172a; color: #e2e8f0; border-radius: 8px; padding: 12px 14px; font-size: 12px; white-space: pre-wrap; border: 0; }
.pm-intro code { background: #f2f3f5; color: #c7254e; padding: 1px 5px; border-radius: 4px; }
.pm-intro pre code { background: none; color: inherit; padding: 0; }
.pm-hide-legacy .pm-legacy { display: none; }

/* 演示 */
.pm-demos { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 14px; }
.pm-demo { border: 1px solid var(--pm-line); border-radius: 12px; padding: 16px; display: flex; gap: 14px; }
.pm-demo-ico { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex: 0 0 44px; }
.pm-demo-ico.frontend { background: linear-gradient(135deg, #0ea5e9, #2563eb); }
.pm-demo-ico.backend { background: linear-gradient(135deg, #6366f1, #7c3aed); }
.pm-demo-ico.h5 { background: linear-gradient(135deg, #f97316, #e11d48); }
.pm-demo-ico.mini { background: linear-gradient(135deg, #10b981, #059669); }
.pm-demo-body { flex: 1; min-width: 0; }
.pm-demo-body h5 { margin: 2px 0 6px; font-size: 15px; font-weight: 600; }
.pm-demo-url { display: block; font-size: 12px; color: var(--pm-sub); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 8px; }
.pm-cred { display: flex; align-items: center; gap: 6px; font-size: 13px; margin-bottom: 4px; }
.pm-cred span { color: var(--pm-sub); width: 34px; }
.pm-cred code { background: #f5f7fa; padding: 1px 6px; border-radius: 4px; color: var(--pm-text); }
.pm-copy { font-size: 12px; color: var(--pm-primary); cursor: pointer; }
.pm-demo-qr { flex: 0 0 104px; text-align: center; font-size: 11px; color: var(--pm-sub); }
.pm-demo-qr img { width: 104px; height: 104px; object-fit: contain; border: 1px solid var(--pm-line); border-radius: 8px; background: #fff; padding: 4px; display: block; margin-bottom: 4px; }
.pm-demo-actions { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
.pm-phone-wrap { display: none; margin-top: 16px; text-align: center; }
.pm-phone { display: inline-block; width: 375px; max-width: 100%; height: 720px; border: 12px solid #0f172a; border-radius: 40px; overflow: hidden; background: #fff; box-shadow: 0 20px 40px rgba(15, 23, 42, .25); }
.pm-phone iframe { width: 100%; height: 100%; border: 0; }

/* 版本记录 */
.pm-timeline { list-style: none; margin: 0; padding: 0 0 0 4px; }
.pm-timeline li { position: relative; padding: 0 0 22px 26px; border-left: 2px solid var(--pm-line); }
.pm-timeline li:last-child { border-left-color: transparent; padding-bottom: 0; }
.pm-timeline li:before { content: ""; position: absolute; left: -7px; top: 3px; width: 12px; height: 12px; border-radius: 50%; background: #fff; border: 2px solid #cbd5e1; }
.pm-timeline li.first:before { border-color: var(--pm-primary); background: var(--pm-primary); }
.pm-timeline h5 { margin: 0 0 6px; font-size: 15px; font-weight: 600; }
.pm-timeline .pm-log { white-space: pre-line; color: #4e5969; font-size: 13px; line-height: 1.7; }

/* 上传向导 */
.pm-steps { display: flex; gap: 0; padding: 18px 24px; }
.pm-step { flex: 1; display: flex; align-items: center; gap: 10px; color: var(--pm-sub); font-size: 14px; }
.pm-step:not(:last-child):after { content: ""; flex: 1; height: 2px; background: var(--pm-line); margin: 0 14px; }
.pm-step b { width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: #f2f3f5; color: var(--pm-sub); font-size: 13px; flex: 0 0 28px; }
.pm-step.on { color: var(--pm-text); font-weight: 600; }
.pm-step.on b { background: var(--pm-primary); color: #fff; }
.pm-step.done b { background: #10b981; color: #fff; }
.pm-drop { border: 2px dashed #cbd5e1; border-radius: 12px; padding: 40px 20px; text-align: center; cursor: pointer; transition: border-color .2s, background .2s; background: #fafbfc; }
.pm-drop:hover, .pm-drop.over { border-color: var(--pm-primary); background: #f5f8ff; }
.pm-drop i { font-size: 38px; color: var(--pm-primary); display: block; margin-bottom: 10px; }
.pm-or { display: flex; align-items: center; gap: 12px; color: var(--pm-sub); margin: 18px 0; font-size: 13px; }
.pm-or:before, .pm-or:after { content: ""; flex: 1; height: 1px; background: var(--pm-line); }
.pm-form-row { display: flex; gap: 16px; flex-wrap: wrap; }
.pm-form-row > .pm-field { flex: 1 1 240px; }
.pm-field { margin-bottom: 16px; }
.pm-field > label { display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px; }
.pm-field > label em { color: #ef4444; font-style: normal; margin-left: 2px; }
.pm-field .pm-hint { font-size: 12px; color: var(--pm-sub); margin-top: 4px; }
.pm-input, .pm-textarea { width: 100%; border: 1px solid #dcdfe6; border-radius: 8px; padding: 8px 12px; font-size: 13px; outline: none; background: #fff; color: var(--pm-text); }
.pm-input { height: 36px; }
.pm-input:focus, .pm-textarea:focus { border-color: var(--pm-primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, .12); }
.pm-checks { display: flex; flex-wrap: wrap; gap: 8px 18px; padding-top: 4px; }
.pm-checks label { font-weight: 400; display: inline-flex; align-items: center; gap: 6px; margin: 0; cursor: pointer; }
.pm-report { border-radius: 10px; border: 1px solid var(--pm-line); padding: 14px 16px; margin-top: 16px; }
.pm-report li { margin-bottom: 4px; }
.pm-shots-edit { display: flex; flex-wrap: wrap; gap: 10px; }
.pm-shot-item { position: relative; width: 150px; height: 96px; border-radius: 8px; overflow: hidden; border: 1px solid var(--pm-line); background: #f5f7fa; }
.pm-shot-item img { width: 100%; height: 100%; object-fit: cover; }
.pm-shot-item .pm-x { position: absolute; top: 4px; right: 4px; width: 22px; height: 22px; border-radius: 50%; background: rgba(15, 23, 42, .6); color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; }
.pm-shot-item .pm-cover { position: absolute; left: 4px; bottom: 4px; background: rgba(37, 99, 235, .9); color: #fff; font-size: 11px; padding: 0 6px; border-radius: 4px; }
.pm-shot-add { width: 150px; height: 96px; border: 2px dashed #cbd5e1; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--pm-sub); cursor: pointer; font-size: 12px; }
.pm-shot-add:hover { border-color: var(--pm-primary); color: var(--pm-primary); }
.pm-demo-row { position: relative; display: flex; gap: 12px; padding: 12px 40px 12px 12px; border: 1px solid var(--pm-line); border-radius: 10px; margin-bottom: 10px; background: #fafbfc; flex-wrap: wrap; }
.pm-demo-row .pm-demo-col { display: flex; flex-direction: column; gap: 6px; flex: 1 1 260px; }
.pm-demo-row .pm-demo-type { flex: 0 0 130px; }
.pm-demo-row .pm-two { display: flex; gap: 6px; }
.pm-demo-row .pm-qr-edit { flex: 0 0 96px; text-align: center; font-size: 12px; }
.pm-demo-row .pm-qr-box { width: 80px; height: 80px; margin: 0 auto 4px; border: 1px dashed #cbd5e1; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: #fff; overflow: hidden; color: #b4b9c1; font-size: 11px; cursor: pointer; }
.pm-demo-row .pm-qr-box img { max-width: 100%; max-height: 100%; }
.pm-demo-row .pm-del { position: absolute; top: 10px; right: 12px; color: #b4b9c1; cursor: pointer; }
.pm-demo-row .pm-del:hover { color: #d4380d; }
.pm-formbar { position: sticky; bottom: 0; z-index: 1001; background: rgba(255, 255, 255, .96); border-top: 1px solid var(--pm-line); padding: 12px 20px; display: flex; gap: 10px; align-items: center; border-radius: 0 0 10px 10px; }
.pm-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.pm-table th { color: var(--pm-sub); font-weight: 500; text-align: left; padding: 10px 12px; border-bottom: 1px solid var(--pm-line); white-space: nowrap; }
.pm-table td { padding: 12px; border-bottom: 1px solid var(--pm-line); vertical-align: middle; }
.pm-table tr:last-child td { border-bottom: 0; }
.pm-pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 12px; padding: 2px 10px; font-size: 12px; line-height: 18px; white-space: nowrap; }
.pm-pill:before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
.pm-pill.pending { color: #c26a00; background: #fff4e5; }
.pm-pill.approved { color: #0e8a4f; background: #e8f7ef; }
.pm-pill.rejected { color: #d4380d; background: #fff1ec; }

@media (max-width: 1100px) {
    .pm-layout { flex-direction: column; }
    .pm-aside { flex-basis: auto; width: 100%; }
}
@media (max-width: 760px) {
    .pm-dh { flex-direction: column; }
    .pm-dh-side { flex-basis: auto; width: 100%; }
    .pm-steps { flex-direction: column; gap: 10px; }
    .pm-step:after { display: none; }
}
CSS
);
