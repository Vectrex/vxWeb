import MessageToast from './components/message-toast';
import Autocomplete from './components/autocomplete';
import DatePicker from './components/datepicker';
import Sortable from './components/sortable';
import SimpleTree from './components/simple-tree';
import Filemanager from './components/filemanager';

import Pagination from './components/zutre/pagination';
import SimpleFetch from './util/simple-fetch';
import PromisedXhr from './util/promised-xhr';
import ProfileForm from './components/forms/profile-form';
import UserForm from './components/forms/user-form';

import { formatFilesize } from './filters';
import { Focus, Bubble} from "./directives";

'./directives';

const Components = {
    MessageToast,
    Autocomplete,
    DatePicker,
    Sortable,
    SimpleTree,
    Pagination,
    ProfileForm,
    Filemanager,
    UserForm,
    SimpleFetch,
    PromisedXhr
};

const Filters = {
    formatFilesize
};

const Directives = {
  Focus,
  Bubble
};

export { Components, Filters, Directives };