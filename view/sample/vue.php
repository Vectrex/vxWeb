<!-- { extend: layout.php @ header_block } -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?= \vxPHP\Application\Application::getInstance()->asset('js/vue/sample.umd.js') ?>"></script>
<!-- { extend: layout.php @ content_block } -->
<h1>VxWeb Vue Components</h1>

<div id="app" class="columns">
    <div class="column col-4 col-sm-12">
        <h2>Autocomplete</h2>
        <div class="my-1">Selectable items on client</div>
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

        <div class="my-1">Selectable items fetched from backend</div>

        <div class="d-inline-block py-1">
            <span class="chip" v-for="(item, ndx) in fetched.picked">{{ item.label }}
                <a href="#" class="btn btn-clear" aria-label="Close" role="button" @click.prevent="fetched.picked.splice(ndx, 1)"></a>
            </span>
        </div>
        <autocomplete
            :search="fetchItems"
            :get-result-value="parseItem"
            v-model="fetched.inputValue"
            placeholder="pick a country"
            @submit="fetchedItemPicked"
            class="d-inline-block"
        >
        </autocomplete>
    </div>

    <div class="column col-4 col-sm-12">
        <h2>Password Input</h2>
        <password-input v-model="password" placeholder="Your password"></password-input>

        <h2>Alerts and Confirms</h2>
        <button class="btn" @click="alertMe">Alert me!</button>
        <button class="btn" @click="confirmThis">Confirm this!</button>
    </div>

    <alert ref="alert"></alert>
    <confirm ref="confirm"></confirm>

</div>

<script>
    const { Autocomplete, Datepicker, Confirm, Alert, PasswordInput } = window.sample.Components;
    const { SimpleFetch, UrlQuery } = window.sample.Util;

    const app = new Vue({

        components: {
            'autocomplete': Autocomplete,
            'datepicker': Datepicker,
            'password-input': PasswordInput,
            'alert': Alert,
            'confirm': Confirm
        },

        el: "#app",

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
                password: ""
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
            }
        }
    })
</script>
