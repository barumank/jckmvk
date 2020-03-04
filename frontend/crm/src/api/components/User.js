import {isEmpty} from "lodash"

export default class User {

    constructor(client) {
        this.client = client;
        this.STATUS_BID = 0;
        this.STATUS_BID_CANCEL = 2;
        this.STATUS_USER = 1;
        this.STATUS_USER_BLOCKED = 3;
        this.ROLE_USER = 'user';
        this.ROLE_ADMIN = 'admin';
        this.ROLE_LIST = [
            {value: this.ROLE_USER, label: 'Пользователь'},
            {value: this.ROLE_ADMIN, label: 'Администратор'},
        ];

    }

    getCurrentUser() {
        let self = this;
        return new Promise((resolve, reject) => {
            self.client
                .get('/api/crm/v1/user/get-current-user')
                .then((response) => {
                    if ('data' in response.data) {
                        resolve(response.data.data.user);
                        return;
                    }
                    resolve(null);
                });
        });
    }

    getUsers(settings = {}) {
        let self = this,
            fields =   'fields' in settings  ? settings.fields : ["id","email","role","name","last_name","image","phone","date_create","status"],
            search = 'search' in settings  ? settings.search : '',
            statuses = 'statuses' in settings ? settings.statuses : [],
            page = 'page' in settings ? settings.page : 1,
            pageSize = 'pageSize' in settings ? settings.pageSize : 30,
            params = `&fields[user]=${fields.join(',')}`;
        if (!isEmpty(search)) {
            params += `&filter[search]=${search}`;
        }
        if (!isEmpty(statuses)) {
            params += `&filter[statuses]=${statuses.join(',')}`;
        }
        if (page) {
            params += `&filter[page]=${page}`;
        }
        if (pageSize) {
            params += `&filter[page_size]=${pageSize}`;
        }
        return new Promise((resolve, reject) => {
            self.client
                .get('/api/crm/v1/user/get-users?' + params)
                .then((response) => {
                    if ('data' in response.data) {
                        resolve(response.data.data);
                        return;
                    }
                    resolve(null);
                });
        });
    }


    getUserById(userId) {
        let self = this;
        let url = `/api/crm/v1/user/get-user?userId=${userId}`;
        return new Promise((resolve, reject) => {
            self.client
                .get(url)
                .then((response) => {
                    if (response.data.status === 'ok') {
                        resolve(response.data.data.user);
                        return;
                    }
                    resolve(null);
                });
        });
    }

    save(userId, data) {

        let self = this;
        let url = `/api/crm/v1/user/save`;
        return new Promise((resolve, reject) => {
            self.client
                .post(url, data)
                .then((response) => {
                    if (response.data.status === 'ok') {
                        resolve(response.data.data.user);
                        return;
                    }
                    resolve(null);
                });
        });
    }

    saveUser(data) {
        let self = this;
        let url = `/api/crm/v1/user/save-user`;
        return new Promise((resolve, reject) => {
            let formData = new FormData();
            for (let key in data) {
                formData.append(key, data[key])
            }
            self.client
                .post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    resolve(response.data);
                });
        });
    }
    saveSettings(data) {
        let self = this;
        let url = `/api/crm/v1/user/settings`;
        return new Promise((resolve, reject) => {
            let formData = new FormData();
            for (let key in data) {
                formData.append(key, data[key])
            }
            self.client
                .post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    resolve(response.data);
                });
        });
    }

}
