import React,{useEffect} from 'react'
import {Message, Segment} from "semantic-ui-react";
import RegistrationForm from "./RegistrationForm/RegistrationForm";
import {connect} from "react-redux";
import {isAccept} from "./selectors";
import {setIsAcceptRegistration} from "../reducer";

const Registration = (props) => {
    const {isAccept, setIsAcceptRegistration} = props;

    useEffect(()=>{
        if(isAccept){
            return()=>setIsAcceptRegistration(false);
        }
    },[isAccept]);

    return (
        <Segment attached='bottom'>
            {isAccept ? (
                <Message positive>
                    <Message.Header>Ваша заявка отправлена!</Message.Header>
                </Message>
            ) : (
                <RegistrationForm/>
            )}
        </Segment>
    );
};

export default connect(
    (state) => ({isAccept: isAccept(state)}),
    {setIsAcceptRegistration}
)(Registration);
