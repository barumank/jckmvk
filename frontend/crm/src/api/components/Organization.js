export default class Organization {

    constructor(client) {
        this.client = client;
    }

    getOrganizations(){
        let self = this;
        return new Promise((resolve, reject) => {
            self.client
                .get('/api/crm/v1/organizations?')
                .then((response) => {
                    if ('data' in response.data) {
                        resolve(response.data.data.organizations);
                        return;
                    }
                    resolve([]);
                });
        });
    }

    save(data) {
        let self = this;
        let url = `/api/crm/v1/organizations/save`;
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

}