<template>
    <div>
        <div class="has-icon-right">
            <input class="text-input" @keydown.esc="handleEsc" @input="handleInput" @focus="handleInput" v-bind="inputProps">
            <i class="form-icon loading" v-if="loading"></i>
        </div>
        <div class="modal-container" v-if="showResults" style="position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%);">
            <div class="modal-header">
                <a href="#close" class="btn btn-clear float-right" aria-label="Close" @click.prevent="showResults = false"></a>
                <div class="modal-title h5">Gefundene Dateien und Ordner&hellip;</div>
            </div>
            <div class="modal-body">
                <div v-for="result in (results.folders || [])" :key="result.id">
                    <slot name="folder" :folder="result">
                        {{ result.name }}
                    </slot>
                </div>
                <div class="divider" v-if="results.folders.length && results.files.length"></div>
                <div v-for="result in (results.files || [])" :key="result.id">
                    <slot name="file" :file="result">
                        {{ result.name }} ({{ result.type }})<br>
                        <span class="text-gray">{{ result.path }}</span>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                value: "",
                results: {
                    files: [],
                    folders: []
                },
                loading: false,
                hideDialog: false
            }
        },

        props: {
            placeholder: { type: String, default: 'Datei/Verzeichnis suchen...' },
            search: { type: Function, required: true }
        },

        computed: {
            inputProps () {
                return {
                    value: this.value,
                    placeholder: this.placeholder
                }
            },
            showResults: {
                get () {
                    return this.results.folders.length || this.results.files.length;
                },
                set (newValue) {
                    if(!newValue) {
                        this.results.folders = [];
                        this.results.files = [];
                    }
                }
            }
        },

        methods: {
            handleInput (event) {
                this.value = event.target.value;
                const search = this.search(this.value);

                if (search instanceof Promise) {
                    this.loading = true;
                    search.then(results => {
                        this.results.files = results.files || [];
                        this.results.folders = results.folders || [];
                        this.loading = false;
                    });
                }
                else {
                    this.results = Object.assign({}, this.results, search);
                }
            },
            handleEsc () {
                this.value = "";
                this.showResults = false;
            }
        }
    }
</script>