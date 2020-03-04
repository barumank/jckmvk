import React from 'react'
import style from "./CategoryList.module.css";
import {Button, Segment} from "semantic-ui-react";
import {Scrollbars} from "react-custom-scrollbars";

const CategoryList = (props) => {

    const renderThumb = ({style, ...props}) => {
        style = {...style, width: '14px', borderRadius: '3px'};
        return (
            <div
                className='groupScroll'
                style={{...style}}
                {...props}/>
        );
    };
    const renderTrack = ({style,...props}) => {
        style = {
            ...style,
            width: '16px',
            bottom: 0,
            top: 0,
            right: '0',
            border: '1px solid #dededf',
            backgroundColor: '#ffffff'
        };
        return (
            <div
                style={{...style}}
                {...props}/>
        );
    };
    const renderView = ({style, ...props}) => {
        style = {...style, paddingRight: '16px'};
        return (
            <div
                style={{...style}}
                {...props}/>
        );
    };

    return (
        <>
            <div className={style.buttonContainer}>
                <Button className={style.back} disabled>Назад</Button>
            </div>
            <div className={style.container}>

                <Segment className={style.categoryContainer}>
                    <Scrollbars
                        renderThumbVertical={renderThumb}
                        renderTrackVertical={renderTrack}
                        renderView={renderView}
                        renderTrackHorizontal={() => (<div style={{display: 'none'}}/>)}
                        renderThumbHorizontal={() => (<div/>)}
                        autoHeight
                        autoHeightMin={0}
                        autoHeightMax={468}
                    >
                        {/* {renderCategory()}*/}
                    </Scrollbars>
                </Segment>
                <Segment className={style.productContainer}>
                    <Scrollbars
                        renderThumbVertical={renderThumb}
                        renderTrackVertical={renderTrack}
                        renderView={renderView}
                        renderTrackHorizontal={() => (<div style={{display: 'none'}}/>)}
                        renderThumbHorizontal={() => (<div/>)}
                        autoHeight
                        autoHeightMin={0}
                        autoHeightMax={468}
                    >
                        {/* {renderProducts()}*/}
                    </Scrollbars>
                </Segment>
            </div>
        </>
    )
};
export default CategoryList;