import MessageToast from '../components/vx-vue/message-toast';
import CircularProgress from '../components/circular-progress';
import Autocomplete from '../components/vx-vue/autocomplete';
import DatePicker from '../components/vx-vue/formelements/datepicker';
import Sortable from '../components/vx-vue/sortable';
import SimpleTree from '../components/vx-vue/simple-tree/simple-tree';
import Filemanager from '../components/filemanager/filemanager';
import Tab from '../components/vx-vue/tab';
import Confirm from '../components/vx-vue/confirm';
import Alert from '../components/vx-vue/alert';
import PasswordInput from '../components/vx-vue/formelements/password-input';
import CookieConsent from '../components/cookie-consent';

import Pagination from '../components/vx-vue/pagination';
import VueCkeditor from "../components/vue-ckeditor";

import SimpleFetch from '../util/simple-fetch';
import PromisedXhr from '../util/promised-xhr';
import UrlQuery from '../util/url-query';
import DateFunctions from '../util/date-functions';

import ProfileForm from '../components/forms/profile-form';
import UserForm from '../components/forms/user-form';
import ArticleForm from '../components/forms/article-form';
import PageForm from '../components/forms/page-form';

import { formatFilesize } from '../filters';
import { Focus } from '../directives';

import { SlickList, SlickItem, plugin as Slicksort, HandleDirective as Handle } from 'vue-slicksort';

const Components = {
    MessageToast,
    CircularProgress,
    Autocomplete,
    DatePicker,
    Sortable,
    SimpleTree,
    Filemanager,
    SlickList, SlickItem,
    Tab,
    Confirm,
    Alert,
    PasswordInput,
    CookieConsent,
    Pagination,
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
    Handle
};

const Util = {
    SimpleFetch,
    PromisedXhr,
    UrlQuery,
    DateFunctions
};

const Plugins = {
    Slicksort
};

export { Components, Filters, Directives, Util, Plugins };