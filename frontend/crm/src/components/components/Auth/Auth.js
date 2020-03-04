import React, {useEffect} from 'react'
import {connect} from "react-redux";
import {isLoading} from './selectors'
import {Dimmer, Loader, Segment} from "semantic-ui-react";
import {getCurrentUser} from "./reducer";


const Auth = (props) => {

    const {isLoading, getCurrentUser, children} = props;

    useEffect(() => {
        getCurrentUser();
    }, []);

    if (isLoading) {
        return (
            <Segment style={{height: '100vh'}}>
                <Dimmer active inverted>
                    <Loader inverted size='huge'>Загрузка...</Loader>
                </Dimmer>
            </Segment>
        );
    } else {
        return children
    }
};

export default connect(state => ({
    isLoading: isLoading(state)
}), {
    getCurrentUser: getCurrentUser
})(Auth);
