import React from 'react'
import style from './Category.module.css'
import {Segment, Image, Icon, Header} from 'semantic-ui-react'
import Popup from "../../../../Popup/Popup";
import {useHistory} from "react-router-dom";

const Category = (props) => {

    const {category,onSetCategoryIdAndPage,categoryId} = props;


    const label = () => {
        if (category.name.length > 27) {
            return (
                <Popup
                    trigger={<Header as='h6' className={style.label} children={category.name}/>}
                    content={category.name}/>
            );
        }
        return (<Header as='h6' className={style.label} children={category.name}/>);
    };

   let className = style.productCategory;
   if(categoryId === category.id){
       className = `${className} ${style.select}`;
   }

   let history = useHistory();
   const selectCategory = ()=>{
       history.push(`?categoryId=${category.id}&page=1`);
       onSetCategoryIdAndPage(category.id);
   };

    return (
        <Segment className={className} onClick={selectCategory}>
            <div className={style.imageContainer}>
                <Image src={category.image}/>
            </div>
            {label()}
        </Segment>
    );
};

export default Category;
