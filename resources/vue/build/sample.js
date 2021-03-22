import Autocomplete from '../components/autocomplete';
import DatePicker from '../components/formelements/datepicker';
import Confirm from '../components/confirm';
import Alert from '../components/alert';
import PasswordInput from '../components/formelements/password-input';
import Pagination from '../components/pagination';

import SimpleFetch from '../util/simple-fetch';
import PromisedXhr from '../util/promised-xhr';
import UrlQuery from '../util/url-query';
import DateFunctions from '../util/date-functions';

import { Focus, Bubble } from '../directives';

const Components = {
    Autocomplete,
    DatePicker,
    Confirm,
    Alert,
    PasswordInput,
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