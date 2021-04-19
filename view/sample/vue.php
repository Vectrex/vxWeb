<!-- { extend: layout.php @ header_block } -->
<script src="https://unpkg.com/vue@next"></script>
<script src="<?= \vxPHP\Application\Application::getInstance()->asset('js/vue/sample.umd.min.js') ?>"></script>
<!-- { extend: layout.php @ content_block } -->
<h1>vxWeb Vue Components</h1>

<div id="app" class="columns">
    <div class="column col-4 col-sm-12 my-2">
        <div class="card" style="height: 100%;">
            <div class="card-header">
                <h2>Autocomplete</h2>
            </div>
            <div class="card-body">
                <p class="my-1">Autocompletes can work both with items already available on the client or items fetched from the backend.</p>
                <div class="my-2">
                    <div class="d-inline-block py-1">
                        <span class="chip" v-for="(item, ndx) in client.picked">{{ item }}
                            <a href="#" class="btn btn-clear" aria-label="Close" role="button" @click.prevent="client.picked.splice(ndx, 1)"></a>
                        </span>
                    </div>
                    <autocomplete
                        :search="findItem"
                        v-model="client.inputValue"
                        placeholder="pick a country"
                        @submit="clientItemPicked"
                        class="d-inline-block"
                    >
                    </autocomplete>
                </div>

                <div>
                    <div class="d-inline-block py-1">
                        <span class="chip" v-for="(item, ndx) in fetched.picked">{{ item.label }}
                            <a href="#" class="btn btn-clear" aria-label="Close" role="button" @click.prevent="fetched.picked.splice(ndx, 1)"></a>
                        </span>
                    </div>
                    <autocomplete
                        :search="fetchItems"
                        :get-result-value="parseItem"
                        v-model="fetched.inputValue"
                        placeholder="fetch a country"
                        @submit="fetchedItemPicked"
                        class="d-inline-block"
                    >
                    </autocomplete>
                </div>
            </div>
        </div>
    </div>

    <div class="column col-4 col-sm-12 my-2">
        <div class="card" style="height: 100%;">
            <div class="card-header">
                <h2>Form Elements</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="password-input">Input element wrapped with a button to change the input type</label>
                    <password-input v-model="form.password" placeholder="Your password" id="password-input"></password-input>
                </div>
                <div class="form-group">
                    <label for="form-select">Select Element</label>
                    <form-select v-model="form.select" :options="this.selectItems" id="form-select"></form-select>
                </div>
                <div class="form-group">
                    <label for="form-switch">Switch Element</label>
                    <form-switch v-model="form.switch" id="form-switch"></form-switch>
                </div>
                <div class="form-group">
                    <label for="form-date">Datepicker</label>
                    <datepicker v-model="form.date" id="form-date"></datepicker>
                </div>
                <div class="form-group">
                    <label for="upload-button">Upload Button</label>
                    <file-button class="btn d-block" v-model="uploads" id="upload-button" :multiple="true"></file-button>
                    <div>
                        <span class="chip" v-for="upload in uploads">{{ upload.name }}, {{ formatSize(upload.size) }}</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div v-for="(item, key) in form">{{ key }} : {{ item }}</div>
            </div>
        </div>
    </div>
    <div class="column col-4 col-sm-12 my-2">
        <div class="card" style="height: 100%;">
            <div class="card-header">
                <h2>Alerts and Confirms</h2>
            </div>
            <div class="card-body">
                <button class="btn" @click="alertMe">Alert me!</button>
                <button class="btn" @click="confirmThis">Confirm this!</button>
                <button class="btn" @click="toast.active = true">Toast me!</button>
            </div>
        </div>
    </div>

    <div class="column col-4 col-sm-12 my-2">
        <div class="card" style="height: 100%;">
            <div class="card-header">
                <h2>Pagination</h2>
            </div>
            <div class="card-body">
                <table>
                    <tr v-for="item in paginatedItems">
                        <td>{{ item }}</td>
                    </tr>
                </table>
                <pagination
                    v-model:page="currentPage"
                    :items="items"
                    :per-page="10"
                    prev-text="&laquo;"
                    next-text="&raquo;"
                >
                </pagination>
            </div>
        </div>
    </div>

    <div class="column col-4 col-sm-12 my-2">
        <div class="card" style="height: 100%;">
            <div class="card-header">
                <h2>Sortable</h2>
            </div>
            <div class="card-body">
                <sortable
                    :rows="sortable.rows"
                    :columns="sortable.cols"
                ></sortable>
            </div>
            <div class="card-footer">
                <button class="btn" @click="sortable.cols.push(sortable.cols.shift())">Shift columns</button>
            </div>
        </div>
    </div>

    <alert ref="alert"></alert>
    <confirm ref="confirm"></confirm>
    <message-toast v-bind="toast" ref="toast" @cancel="toast.active = false" @timeout="toast.active = false"></message-toast>
</div>

<script>
    const { Autocomplete, Datepicker, Confirm, Alert, MessageToast, PasswordInput, Pagination, FormSelect, FormSwitch, FormFileButton, Sortable } = window.sample.Components;
    const { SimpleFetch, UrlQuery, BytesToSize } = window.sample.Util;

    Vue.createApp({

        components: {
            'autocomplete': Autocomplete,
            'datepicker': Datepicker,
            'form-select': FormSelect,
            'form-switch': FormSwitch,
            'password-input': PasswordInput,
            'file-button': FormFileButton,
            'alert': Alert,
            'confirm': Confirm,
            'message-toast': MessageToast,
            'pagination': Pagination,
            'sortable': Sortable
        },

        routes: {
            fetchItems: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('sample_fetch')->getUrl() ?>"
        },

        data () {
            return {
                items: [
                    "<?= implode('","', $this->items) ?>"
                ],
                client: {
                    picked: [],
                    inputValue:""
                },
                fetched: {
                    picked: [],
                    inputValue:""
                },
                form: {
                    password: "",
                    select: "",
                    switch: false,
                    date: new Date()
                },
                toast: {
                    message: "Howdy!",
                    classname: "toast-success",
                    active: false
                },
                sortable: {
                    rows: [
                        { key: 1, name: 'Linda', role: 'Sarah', yob: 1956 },
                        { key: 2, name: 'Robert', role: 'T1000', yob: 1958 },
                        { key: 3, name: 'Arnold', role: 'T800', yob: 1947 },
                        { key: 4, name: 'Edward', role: 'John', yob: 1977 }
                    ],
                    cols: [
                        { label: 'Name', prop: 'name', sortable: true },
                        { label: 'Role', prop: 'role', sortable: true  },
                        { label: 'Born in', prop: 'yob', sortable: true  }
                    ]
                },
                currentPage: 1,
                entriesPerPage: 10,
                uploads: []
            }
        },

        computed: {
            paginatedItems () {
                return this.items.slice((this.currentPage - 1) * this.entriesPerPage, this.currentPage * this.entriesPerPage);
            },
            selectItems () {
                return this.items.filter(item => item.length > 10);
            }
        },

        methods: {
            findItem (query) {
                return this.items.filter (item => item.toLowerCase().indexOf(query.toLowerCase()) !== -1);
            },
            async fetchItems (query) {
                return await SimpleFetch(UrlQuery.create(this.$options.routes.fetchItems, { query: query }));
            },
            clientItemPicked (item) {
                if (item && this.client.picked.indexOf(item) === -1) {
                    this.client.picked.push(item);
                }
                this.client.inputValue = "";
            },
            parseItem (item) {
                return item.label + " (" + item.key + ")";
            },
            fetchedItemPicked (item) {
                if(this.fetched.picked.findIndex(f => f.key === item.key) === -1) {
                    this.fetched.picked.push(item);
                }
                this.fetched.inputValue = "";
            },
            async alertMe () {
                await this.$refs.alert.open('Skynet...', "...begins to learn at a geometric rate.");
            },
            async confirmThis () {
                if (!await this.$refs.confirm.open('Pull...', "...the plug on Skynet?")) {
                    await this.$refs.alert.open('Judgement Day', " Three billion human lives ended on August 29th, 1997.");
                }
            },
            formatSize (size) {
                return BytesToSize.formatBytes(size);
            }
        }
    }).mount ("#app");
</script>