import React from "react";

const Detail = (props) => {
    const {productName} = props;

    return(
            <span>{productName}</span>
    );
};

export default Detail;