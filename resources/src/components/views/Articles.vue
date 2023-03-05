<script setup>
  import Sortable from "@/components/vx-vue/sortable.vue";
  import Pagination from "@/components/vx-vue/pagination.vue";
  import FilterForm from "@/components/views/articles/FilterForm.vue";
</script>

<template>
  <div class="h-16 flex items-center justify-between">
    <filter-form v-model="filter" :options="{ categories: [ { key: 0, label: '(Alle Kategorien)' }, ...categories ] }" />
    <pagination class="w-1/3" :total="filteredArticles.length" :per-page="pagination.entriesPerPage" v-model:page="pagination.page" marker-position="below" />
  </div>
  <sortable
      :rows="paginatedArticles"
      :columns="cols"
      :sort-prop="initSort.column"
      :sort-direction="initSort.dir"
      :offset="(currentPage - 1) * entriesPerPage"
      :count="entriesPerPage"
      @after-sort="storeSort"
      ref="sortable"
  >
    <template v-slot:catId="slotProps">
      {{ categories.find(c => c.id === slotProps.row.catId).label }}
    </template>
    <template v-slot:pub="slotProps">
      <input type="checkbox" class="form-checkbox" :checked="slotProps.row.pub" @click="publish(slotProps.row)">
    </template>
    <template v-slot:marked="slotProps">
      <input type="checkbox" class="form-checkbox" disabled="disabled" :checked="slotProps.row.marked">
    </template>
  </sortable>

</template>

<script>
export default {
  name: "Articles",
  emits: ['notify'],
  inject: ['api'],
  data() {
    return {
      articles: [],
      categories: [],
      pagination: {
        page: 1,
        entriesPerPage: 20
      },
      cols: [
        { label: "Kategorie", sortable: true, prop: "catId" },
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
      },
      entriesPerPage: 20,
      currentPage: 1
    }
  },

  computed: {
    filteredArticles () {
      const titleFilter = this.filter.title.toLowerCase();
      return this.articles.filter(item => (!this.filter.cat || this.filter.cat === item.catId) && (!this.filter.title || item.title.toLowerCase().indexOf(titleFilter) !== -1));
    },
    paginatedArticles () {
      return this.filteredArticles.slice((this.pagination.page - 1) * this.pagination.entriesPerPage, this.pagination.page * this.pagination.entriesPerPage);
    }
  },

  async created () {
    let lsValue = window.localStorage.getItem(window.location.origin + "/admin/articles__sort__");
    if(lsValue) {
      this.initSort = JSON.parse(lsValue);
    }

    let response = await this.$fetch(this.api + "articles");

    this.categories = response.categories || [];

    this.categories.forEach(item => item.key = item.id);
    this.articles = response.articles || [];
  },

  methods: {
    storeSort () {
    },
    publish () {
    }
  }
}
</script>
