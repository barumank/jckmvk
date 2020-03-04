import {Popup} from "semantic-ui-react";
import React from "react";

export default (props)=>{
    return (
        <Popup
            {...props}
            popperModifiers={{
                preventOverflow: {
                    boundariesElement: "offsetParent"
                }
            }}
        />
    )
}
