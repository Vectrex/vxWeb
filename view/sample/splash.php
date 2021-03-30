<!-- { extend: sample/layout.php @ content_block } -->
<main style="height: 100vh;" class="bg-gray">
    <div class="empty">
        <div class="empty-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="383.42" height="100.84" viewBox="0 0 101.45 26.69"><defs><filter id="a" color-interpolation-filters="sRGB"><feGaussianBlur result="blur" stdDeviation="1 1"/></filter></defs><path fill-opacity=".31" d="M35.07 17.1a7.26 7.26 0 007.35 7.34c2.17 0 4.14-.97 5.4-2.45 4.46 5.04 12.8 1.84 12.73-4.89V2.22h-3.62V17.1c0 2.07-1.6 3.95-3.66 3.95-1.9 0-3.6-1.88-3.6-3.95V2.22h-3.63V17.1c0 2.07-1.62 3.95-3.62 3.95-2.08 0-3.73-1.88-3.73-3.95V2.22h-3.62zm28.07-.42c0 4.1 3.3 7.47 7.45 7.47h6.97v-3.4h-5.44l5.76-5.72C76.02 6.2 63 7.65 63.14 16.68zm4.96 2.94a3.99 3.99 0 01-1.33-2.94c0-2.17 1.75-4.04 3.82-4.04 1.17 0 2.46.68 2.98 1.5zm15.84-2.68V12.9h3.72c2.3 0 3.92 1.87 3.92 4.04 0 2.13-1.62 4.1-3.92 4.1-2 0-3.72-1.97-3.72-4.1zm-3.63 0a7.4 7.4 0 007.35 7.5 7.49 7.49 0 100-14.97h-3.72V2.22h-3.63z" filter="url(#a)"/><path fill="#ff9400" d="M5.56 24.15h5.64l5.6-14.68h-3.98l-4.44 11.8-4.47-11.8H0zm12.05 0h3.63V21.7a3.77 3.77 0 013.66-3.79 3.8 3.8 0 013.78 3.8v2.44h3.63V21.7c0-2.14-1-4.1-2.46-5.54a7.41 7.41 0 002.46-5.46V9.47H28.7v1.23a3.82 3.82 0 01-3.78 3.81 3.8 3.8 0 01-3.66-3.8V9.46h-3.63v1.23c0 2.23 1 4.1 2.46 5.46a7.84 7.84 0 00-2.46 5.54z"/><path fill="#0089cc" d="M35.07 17.1a7.26 7.26 0 007.35 7.34c2.17 0 4.14-.97 5.4-2.45 4.46 5.04 12.8 1.84 12.73-4.89V2.22h-3.62V17.1c0 2.07-1.6 3.95-3.66 3.95-1.9 0-3.6-1.88-3.6-3.95V2.22h-3.63V17.1c0 2.07-1.62 3.95-3.62 3.95-2.08 0-3.73-1.88-3.73-3.95V2.22h-3.62zm28.07-.42c0 4.1 3.3 7.47 7.45 7.47h6.97v-3.4h-5.44l5.76-5.72C76.02 6.2 63 7.65 63.14 16.68zm4.96 2.94a3.99 3.99 0 01-1.33-2.94c0-2.17 1.75-4.04 3.82-4.04 1.17 0 2.46.68 2.98 1.5zm15.84-2.68V12.9h3.72c2.3 0 3.92 1.87 3.92 4.04 0 2.13-1.62 4.1-3.92 4.1-2 0-3.72-1.97-3.72-4.1zm-3.63 0a7.4 7.4 0 007.35 7.5 7.49 7.49 0 100-14.97h-3.72V2.22h-3.63z"/></svg>
        </div>
        <p class="empty-title">
            vxWeb is a spartan CMS atop the <a href="https://github.com/Vectrex/vxPHP" target="_blank">vxPHP framework</a>.
        </p>
        <p class="empty-subtitle">
            The backend utilizes <a href="https://picturepan2.github.io/spectre/" target="_blank">spectre.css</a> for styling and homegrown Vue components for user-friendly functionality.
        </p>
        <p class="empty-subtitle">
            This sample pages reside in the <code>sample</code> folders in <code>/view/</code> and <code>/src/Controller/</code> and are configured by <code>ini/sample.ini.xml</code>.<br>
            All these files can be safely removed.
        </p>
        <div class="empty-action">
            <a class="btn" href="<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('sample_vue')->getUrl() ?>">Vue examples</a>
            <a class="btn" href="<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('sample_form')->getUrl() ?>">HTML form example</a>
        </div>
    </div>
</main>
<!-- @see https://github.com/tholman/github-corners -->
<a href="https://github.com/Vectrex/vxWeb" target="_blank" class="github-corner" aria-label="View source on GitHub"><svg width="80" height="80" viewBox="0 0 250 250" style="fill:#151513; color:#fff; position: absolute; top: 0; border: 0; right: 0;" aria-hidden="true"><path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path><path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path><path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path></svg></a><style>.github-corner:hover .octo-arm{animation:octocat-wave 560ms ease-in-out}@keyframes octocat-wave{0%,100%{transform:rotate(0)}20%,60%{transform:rotate(-25deg)}40%,80%{transform:rotate(10deg)}}@media (max-width:500px){.github-corner:hover .octo-arm{animation:none}.github-corner .octo-arm{animation:octocat-wave 560ms ease-in-out}}</style>
