export default class Category {

    constructor(client) {
        this.client = client;
        this.TYPE_CUSTOM = 1;
        this.TYPE_BASE = 0;
        this.TYPE_LIST = [
            {value: this.TYPE_CUSTOM, label: 'Пользвательская'},
            {value: this.TYPE_BASE, label: 'Базовая'},
        ];
        this.TYPE_MAP = new Map();
        this.TYPE_MAP.set(this.TYPE_BASE,'Базовые');
        this.TYPE_MAP.set(this.TYPE_CUSTOM,'Пользовательские')
    }

    getCategoryByParentId(type, parentId = null) {
        let self = this;
        let params = `&fields[category]=id,name,image,user_id&filter[parent_id]=${parentId}` +
            `&filter[type]=${type}`;
        let url = '/api/crm/v1/categories?' + params;
        return new Promise((resolve, reject) => {
            self.client
                .get(url)
                .then((response) => {
                    if ('data' in response.data) {
                        resolve(response.data.data.categories);
                        return;
                    }
                    resolve([]);
                });
        });
    }
    getParentCategoryById(categoryId) {
        let self = this;
        let params = `&fields[category]=id,name&filter[category_id]=${categoryId}`;
        let url = '/api/crm/v1/categories/get-parent?' + params;
        return new Promise((resolve, reject) => {
            if (categoryId === '0' || categoryId === 0) {
                resolve([]);
                return;
            }
            self.client
                .get(url)
                .then((response) => {
                    if ('data' in response.data) {
                        resolve(response.data.data.parents);
                        return;
                    }
                    resolve([]);
                });
        });
    }

    searchCategory(type, search='') {
        let self = this;
        let params = `&fields[category]=id,name,image,user_id` +
            `&filter[type]=${type}&filter[search]=${search}`;

        return new Promise((resolve, reject) => {
            if(search===''){
                resolve([]);
                return;
            }
            self.client
                .get('/api/crm/v1/categories?' + params)
                .then((response) => {
                    if ('data' in response.data) {
                        resolve(response.data.data.categories);
                        return;
                    }
                    resolve([]);
                });
        });
    }

    getCategoryById(categoryId) {
        let self = this;
        let params = `&category_id=${categoryId}`;
        return new Promise((resolve, reject) => {
            if (categoryId === '0' || categoryId === 0) {
                resolve(null);
                return;
            }
            self.client
                .get('/api/crm/v1/categories/get-by-id?' + params)
                .then((response) => {
                    if ('data' in response.data) {
                        resolve(response.data.data.category);
                        return;
                    }
                    resolve(null);
                });
        });
    }

    getCategoriesGetByType(type) {
        let self = this;
        let params = `&type=${type}`;
        return new Promise((resolve, reject) => {
            self.client
                .get('/api/crm/v1/categories/get-by-type?' + params)
                .then((response) => {
                    if ('data' in response.data) {
                        resolve(response.data.data.list);
                        return;
                    }
                    resolve(null);
                });
        });
    }

    save(data) {
        let self = this;
        let url = `/api/crm/v1/categories/save`;
        return new Promise((resolve, reject) => {
            let formData = new FormData();
            for (let key in data) {
                formData.append(key, data[key]);
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

    deleteById(id) {
        let self = this;
        let params = `&category_id=${id}`;
        return new Promise((resolve, reject) => {
            self.client
                .delete(`/api/crm/v1/categories/delete${params}`)
                .then((response) => {
                    resolve(response.data);
                });
        });
    }
}
