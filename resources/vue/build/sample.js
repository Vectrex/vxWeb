import Autocomplete from '../components/vx-vue/autocomplete';
import DatePicker from '../components/vx-vue/formelements/datepicker';
import Confirm from '../components/vx-vue/confirm';
import Alert from '../components/vx-vue/alert';
import PasswordInput from '../components/vx-vue/formelements/password-input';
import Pagination from '../components/vx-vue/pagination';
import FormSelect from '../components/vx-vue/formelements/form-select';
import FormSwitch from '../components/vx-vue/formelements/form-switch';

import SimpleFetch from '../util/simple-fetch';
import PromisedXhr from '../util/promised-xhr';
import UrlQuery from '../util/url-query';
import DateFunctions from '../util/date-functions';

import { Focus, Bubble } from '../directives';

const Components = {
    FormSelect,
    FormSwitch,
    PasswordInput,
    Autocomplete,
    DatePicker,
    Confirm,
    Alert,
    Pagination
};

const Directives = {
    Focus,
    Bubble
};

const Util = {
    SimpleFetch,
    PromisedXhr,
    UrlQuery,
    DateFunctions
};

export { Components, Directives, Util };