<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<div id="app">

    <h1>Artikel &amp; News</h1>

    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-right btn-primary" data-icon="&#xe018;" href="<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('article_add')->getUrl() ?>">Artikel anlegen</a>
        <select v-model="filter.cat" class="form-select col-2 mx-2">
            <option value="">(Alle Kategorien)</option>
            <option v-for="cat in categories" :value="cat.id">{{ cat.label }}</option>
        </select>
        <input v-model="filter.title" class="form-input col-2 mx-2" placeholder="Titel filtern...">
    </div>

    <sortable
        :rows="filteredArticles"
        :columns="cols"
        :sort-prop="initSort.column"
        :sort-direction="initSort.dir"
        @after-sort="storeSort"
        ref="sortable"
    >
        <template v-slot:pub="slotProps">
            <label class="form-switch">
                <input type="checkbox" :checked="slotProps.row.pub" @click="publish(slotProps.row)">
                <i class="form-icon"></i>
            </label>
        </template>
        <template v-slot:marked="slotProps">
            <label class="form-checkbox">
                <input type="checkbox" disabled="disabled" :checked="slotProps.row.marked">
                <i class="form-icon"></i>
            </label>
        </template>
        <template v-slot:action="slotProps">
            <a class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" :href="'<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('article_edit')->getUrl() ?>?id=' + slotProps.row.key">&#xe002;</a>
            <a class="btn webfont-icon-only tooltip tooltip-left" data-tooltip="Löschen" href="#" @click.prevent="del(slotProps.row)">&#xe011;</a>
        </template>
    </sortable>
    <confirm ref="confirm" :config="{ cancelLabel: 'Abbrechen', okLabel: 'Löschen', okClass: 'btn-error' }"></confirm>
</div>

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>
    const { Sortable, Confirm } = window.vxweb.Components;
    const SimpleFetch = window.vxweb.Util.SimpleFetch;

    let app = new Vue({

        el: "#app",
        components: { 'sortable': Sortable, 'confirm': Confirm },

        routes: {
            init: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('articles_init')->getUrl() ?>",
            publish: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('article_publish')->getUrl() ?>",
            delete: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('article_del')->getUrl() ?>"
        },

        data: {
            articles: [],
            categories: [],
            cols: [
                { label: "Kategorie", sortable: true, prop: "cat" },
                { label: "Titel", sortable: true, prop: "title" },
                { label: "Pub", sortable: true, prop: "pub" },
                { label: "*", sortable: true, prop: "marked" },
                { label: "Artikeldatum", sortable: true, prop: "date" },
                { label: "Anzeige von", sortable: true, prop: "from" },
                { label: "Anzeige bis", sortable: true, prop: "until" },
                { label: "Sortierziffer", sortable: true, prop: "sort" },
                { label: "Angelegt/aktualisiert", sortable: true, prop: "updated" },
                { label: "", prop: "action" }
            ],
            initSort: {},
            filter: {
                cat: '',
                title: ''
            }
        },

        computed: {
            filteredArticles() {
                const titleFilter = this.filter.title.toLowerCase();
                return this.articles.filter(item => (!this.filter.cat || this.filter.cat === item.catId) && (!this.filter.title || item.title.toLowerCase().indexOf(titleFilter) !== -1));
            }
        },

        async created () {
            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/articles__sort__");
            if(lsValue) {
                this.initSort = JSON.parse(lsValue);
            }

            let response = await SimpleFetch(this.$options.routes.init);

            this.categories = response.categories || [];

            let catLookup = {};
            this.categories.forEach(item => catLookup[item.id] = item);
            this.articles = response.articles || [];
            this.articles.forEach(item => item.cat = catLookup[item.catId].label);
        },

        methods: {
            async publish (row) {
                this.$set(row, 'pub', !row.pub);
                let response = await SimpleFetch(this.$options.routes.publish, 'POST', {}, JSON.stringify({id: row.key, state: row.pub }));
                if(!response.success) {
                    this.$set(row, 'pub', !row.pub);
                }
            },
            async del (row) {
                if(await this.$refs.confirm.open('Artikel löschen', "Soll der Artikel wirklich gelöscht werden?")) {
                    let response = await SimpleFetch(this.$options.routes.delete + '?id=' + row.key, 'DELETE');
                    if(response.success) {
                        this.articles.splice(this.articles.findIndex(item => row === item), 1);
                    }
                }
            },
            storeSort () {
                window.localStorage.setItem(window.location.origin + "/admin/articles__sort__", JSON.stringify({ column: this.$refs.sortable.sortColumn.prop, dir: this.$refs.sortable.sortDir }));
            }
        }
    });
</script>