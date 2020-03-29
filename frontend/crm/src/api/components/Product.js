export default class Product {
    constructor(client) {
        this.client = client
    }

    getProductsAndAttributes(categoryId, type, page) {
        let self = this;
        let params = `&fields[product]=id,name&fields[productAttribute]=attribute_id,product_id,value` +
            `&filter[category_id]=${categoryId}&filter[type]=${type}&filter[page]=${page}` +
            `&include=productAttributes,attributeNames`;
        return new Promise((resolve, reject) => {
            self.client
                .get('/api/crm/v1/products?' + params)
                .then((response) => {
                    let out = {
                        attributeNames: [],
                        productAttributes: [],
                        pagination: [],
                        products: []
                    };
                    if (!('data' in response.data)) {
                        resolve([]);
                        return;
                    }
                    out.attributeNames = response.data.data.attributeNames;
                    out.productAttributes = response.data.data.productAttributes;
                    out.pagination = response.data.data.pagination;
                    out.products = response.data.data.products;
                    resolve(out);
                });

        });
    }

    searchProduct(search,type,categoryId, page) {
        let self = this;
        let params = `&fields[product]=id,name&fields[productAttribute]=attribute_id,product_id,value` +
            `&&filter[category_id]=${categoryId}&filter[search]=${search}&filter[type]=${type}&filter[page]=${page}` +
            `&include=productAttributes,attributeNames`;
        return new Promise((resolve, reject) => {
            self.client
                .get('/api/crm/v1/products?' + params)
                .then((response) => {
                    let out = {
                        attributeNames: [],
                        productAttributes: [],
                        pagination: [],
                        products: []
                    };
                    if (!('data' in response.data)) {
                        resolve([]);
                        return;
                    }
                    out.attributeNames = response.data.data.attributeNames;
                    out.productAttributes = response.data.data.productAttributes;
                    out.pagination = response.data.data.pagination;
                    out.products = response.data.data.products;
                    resolve(out);
                });

        });
    }

    getProductAttributeGroups() {
        let self = this;
        return new Promise((resolve, reject) => {
            self.client
                .get('/api/crm/v1/products/get_product_attribute_groups')
                .then((response) => {
                    let out = {
                        productAttributeGroups: []
                    };
                    if (!('data' in response.data)) {
                        resolve([]);
                        return;
                    }
                    out.productAttributeGroups = response.data.data.productAttributeGroups;
                    resolve(out);
                });
        });
    }

    save(data) {
        let self = this;
        let url = `/api/crm/v1/products/save`;
        return new Promise((resolve, reject) => {
            self.client
                .post(url, data)
                .then((response) => {
                    resolve(response.data);
                });
        });
    }

    saveAnalogGroup(data) {
        let self = this;
        let url = `/api/crm/v1/products/save_analog_group`;
        return new Promise((resolve, reject) => {
            self.client
                .post(url, data)
                .then((response) => {
                    resolve(response.data);
                });
        });
    }

    getProduct(productId) {
        let self = this;
        let params = `?product_id=${productId}`;
        return new Promise((resolve, reject) => {
            self.client
                .get('/api/crm/v1/products/get_product' + params)
                .then((response) => {
                    let out = {
                        product: []
                    };
                    if (!('data' in response.data)) {
                        resolve([]);
                        return;
                    }
                    out.product = response.data.data.product;
                    resolve(out);
                });
        });
    }

    getAnalogProductsAndAttributes(productId, groupId) {
        let self = this;
        let params = `&fields[product]=id,name&fields[productAttribute]=attribute_id,product_id,value` +
            `&filter[product_id]=${productId}&filter[group_id]=${groupId}&include=productAttributes,attributeNames`;
        return new Promise((resolve, reject) => {
            self.client
                .get('/api/crm/v1/products/get_product_similar?' + params)
                .then((response) => {
                    let out = {
                        attributeNames: [],
                        productAttributes: [],
                        products: []
                    };
                    if (!('data' in response.data)) {
                        resolve([]);
                        return;
                    }
                    out.attributeNames = response.data.data.attributeNames;
                    out.productAttributes = response.data.data.productAttributes;
                    out.products = response.data.data.products;
                    resolve(out);
                });
        });
    }
}
