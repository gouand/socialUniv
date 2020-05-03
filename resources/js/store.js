export default {
    strict: true,
    state: {
        contacts: []
    },
    mutations: {
        getContacts(state, payload){
            state.contacts = payload;
        }
    },
    actions: {
        getContactsAction: (context ) => {
            //context.commit('getContacts', [{name : "Andrei Grisy"}]);
                axios.get('/contacts').then(response => {
                    console.log("Run Action: " + response)
                   return context.commit('getContacts',response.data);
                });

        },

    }

};
