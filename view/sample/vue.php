<!-- { extend: layout.php @ header_block } -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?= \vxPHP\Application\Application::getInstance()->asset('js/vue/sample.umd.js') ?>"></script>
<!-- { extend: layout.php @ content_block } -->
<h1>VxWeb Vue Components</h1>

<div id="app">
    <h2>Autocomplete</h2>
    <span class="chip" v-for="(item, ndx) in picked">{{ item }}
        <a href="#" class="btn btn-clear" aria-label="Close" role="button" @click.prevent="picked.splice(ndx, 1)"></a>
    </span>
    <autocomplete
        :search="findItem"
        v-model="inputValue"
        placeholder="pick a country"
        @submit="itemPicked"
        class="d-inline-block"
    >
    </autocomplete>
</div>

<script>
    const { Autocomplete, Datepicker, Confirm, Alert } = window.sample.Components;
    const SimpleFetch = window.sample.Util.SimpleFetch;

    const app = new Vue({

        components: {
            'autocomplete': Autocomplete,
            'datepicker': Datepicker
        },

        el: "#app",

        data () {
            return {
                items: [
                    "<?= implode('","', $this->items) ?>"
                ],
                picked: [],
                inputValue: ""
            }
        },

        methods: {
            findItem (query) {
                return this.items.filter (item => item.toLowerCase().indexOf(query.toLowerCase()) !== -1);
            },

            itemPicked (item) {
                if (item && this.picked.indexOf(item) === -1) {
                    this.picked.push(item);
                }
                this.inputValue = "";
            }
        }
    });
</script>
