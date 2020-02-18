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
            <tr v-for="row in sortedRows" :key="row.key" :class="row.cssClass">
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
            sortProp: {
                type: String
            },
            sortDirection: {
                type: String,
                validator (val) {
                    return !val || ['asc', 'desc'].indexOf(val) !== -1;
                }
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
                if(!this.sortColumn) {
                    return this.rows;
                }
                return this.doSort(this.sortColumn, this.sortDir);
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
            },
            doSort (column, dir) {
                let rows = this.rows;

                if (dir === 'asc' && column.sortAscFunction) {
                    rows.sort (column.sortAscFunction);
                }
                else if (dir === 'desc' && column.sortDescFunction) {
                    rows.sort (column.sortDescFunction);
                }
                else {
                    let prop = column.prop;

                    rows.sort((a, b) => {
                        if (a[prop] < b[prop]) {
                            return dir === "asc" ? -1 : 1;
                        }
                        if (a[prop] > b[prop]) {
                            return dir === "asc" ? 1 : -1;
                        }
                        return 0;
                    });
                }

                return rows;
            }
        }
    }
</script>