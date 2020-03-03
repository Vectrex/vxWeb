import MessageToast from './components/message-toast';
import CircularProgress from './components/circular-progress';
import Autocomplete from './components/autocomplete';
import DatePicker from './components/datepicker';
import Sortable from './components/sortable';
import SimpleTree from './components/simple-tree';
import Filemanager from './components/filemanager';
import FilesortList from './components/filesort-list';
import Tab from './components/tab';

import ZPagination from './components/zutre/pagination';
import ZLink from './components/zutre/link';
import VueCkeditor from "./components/VueCkeditor";

import SimpleFetch from './util/simple-fetch';
import PromisedXhr from './util/promised-xhr';

import ProfileForm from './components/forms/profile-form';
import UserForm from './components/forms/user-form';
import ArticleForm from './components/forms/article-form';
import PageForm from  './components/forms/page-form';

import { formatFilesize } from './filters';
import { Focus, Bubble } from './directives';

import { ContainerMixin, ElementMixin, HandleDirective } from 'vue-slicksort';

const Components = {
    MessageToast,
    CircularProgress,
    Autocomplete,
    DatePicker,
    Sortable,
    SimpleTree,
    Filemanager,
    FilesortList,
    Tab,
    ZPagination,
    ZLink,
    VueCkeditor,
    ProfileForm,
    UserForm,
    ArticleForm,
    PageForm
};

const Filters = {
    formatFilesize
};

const Directives = {
    Focus,
    Bubble,
    HandleDirective
};

const Mixins = {
    ContainerMixin,
    ElementMixin
};

const Util = {
    SimpleFetch,
    PromisedXhr
};

export { Components, Filters, Directives, Mixins, Util };