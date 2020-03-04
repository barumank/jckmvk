import React from 'react'
import style from './ProductCategory.module.css'
import {Segment, Image, Icon, Header} from 'semantic-ui-react'
import Popup from "../Popup/Popup";

const ProductCategory = (props) => {

    const {
        id,
        name,
        image,
        userId,
        currentCategoryId,
        hasChildren,
        onSelectCategory,
        onOpenCategory,
        onEditCategory,
        onDeleteCategory
    } = props;
    const label = () => {
        let labelName = name;
        if (name.length > 27) {
            labelName = name.slice(0, 27) + '...';
            return (<Popup
                trigger={<Header as='h6' className={style.label} children={labelName}/>}
                content={name}
            />);
        }
        return (<Header as='h6' className={style.label} children={labelName}/>);
    };

    let className = style.productCategory;
    if (currentCategoryId === id) {
        className = `${className} ${style.select}`;
    }

    const getImage = ()=>{
        if(image){
            return (<Image src={`/upload/users/${userId}/category_images/${image}`}/>);
        }
        return (<Image src='/img/no_image.png'/>);
    };
    return (
        <Segment className={className}
                 onClick={() => {
                     onSelectCategory(id)
                 }}
                 onDoubleClick={() => {
                     if (hasChildren) {
                         onOpenCategory(id)
                     }
                 }}>
            <div className={style.imageContainer}>
                <Icon name='pencil alternate' className={style.editIcon} onClick={(e)=>{
                    e.stopPropagation();
                    onEditCategory(id);
                }}/>
                {getImage()}
                <Icon name='trash alternate' className={style.deleteIcon} onClick={(e)=>{
                    e.stopPropagation();
                    onDeleteCategory(id);
                }}/>
            </div>
            {label()}
        </Segment>
    );
};

export default ProductCategory;
