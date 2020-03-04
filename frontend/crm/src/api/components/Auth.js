export default class Auth {

    constructor(client) {
        this.client = client;
    }

    registration(values){
        let self = this;
        return new Promise((resolve, reject) => {
            self.client
                .post('/api/crm/v1/auth/registration',values)
                .then((response) => {
                    resolve(response.data);
                });
        });
    }
    login(values){
        let self = this;
        return new Promise((resolve, reject) => {
            self.client
                .post('/api/crm/v1/auth/login',values)
                .then((response) => {
                    resolve(response.data);
                });
        });
    }
    logout(){
        let self = this;
        return new Promise((resolve, reject) => {
            self.client
                .post('/api/crm/v1/auth/logout')
                .then((response) => {
                    resolve(response.data);
                });
        });
    }
}
