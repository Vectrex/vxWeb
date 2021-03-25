<template>
    <table class="table table-striped">
        <thead>
        <tr>
            <th
                v-for="column in columns"
                :class="[
                    'vx-sortable-header',
                    column.cssClass,
                    sortColumn === column ? sortDir : null,
                    column.width
                ]"
                @click="column.sortable ? clickSort(column) : null"
            >
                <slot :name="column.prop + '-header'" :column="column">
                    {{ column.label }}
                </slot>
            </th>
        </tr>
        </thead>
        <tbody>
            <tr v-for="row in sortedRows" :key="row[keyProperty]" :class="row.cssClass">
                <td v-for="column in columns" :class="{ 'active': sortColumn === column }"><slot :name="column.prop" :row="row">{{ row[column.prop] }}</slot></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: 'sortable',

        props: {
            columns: {
                type: Array,
                required: true,
                validator (val) {
                    for(let l = val.length; l--;) {
                        if(val[l].label === undefined || val[l].prop === undefined) {
                            return false;
                        }
                    }
                    return true;
                }
            },
            rows: {
                type: Array,
                required: true
            },
            offset: {
                type: Number,
                default: null
            },
            count: {
                type: Number,
                default: null
            },
            sortProp: {
                type: String
            },
            sortDirection: {
                type: String,
                validator (val) {
                    return !val || ['asc', 'desc'].indexOf(val) !== -1;
                }
            },
            keyProperty: {
              type: String,
              default: 'key'
            }
        },

        data() {
            return {
                sortColumn: this.columns.find(item => item.prop === this.sortProp),
                sortDir: this.sortDirection
            };
        },

        computed: {
            sortedRows () {
                let rows = this.rows.slice();

                if(this.sortColumn) {
                    if (this.sortDir === 'asc' && this.sortColumn.sortAscFunction) {
                        rows.sort (this.sortColumn.sortAscFunction);
                    }
                    else if (this.sortDir === 'desc' && this.sortColumn.sortDescFunction) {
                        rows.sort (this.sortColumn.sortDescFunction);
                    }
                    else {
                        let prop = this.sortColumn.prop;

                        rows.sort((a, b) => {
                            if (a[prop] < b[prop]) {
                                return this.sortDir === "asc" ? -1 : 1;
                            }
                            if (a[prop] > b[prop]) {
                                return this.sortDir === "asc" ? 1 : -1;
                            }
                            return 0;
                        });
                    }
                }

                let offset = this.offset || 0, count = this.count || rows.length;

                return rows.slice(offset, offset + count);
            }
        },

        methods: {
            clickSort (column) {
                this.$emit('before-sort');
                if(this.sortColumn === column) {
                    this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
                }
                else {
                    this.sortColumn = column;
                }
                this.$nextTick( () => this.$emit('after-sort') );
            }
        }
    }
</script>