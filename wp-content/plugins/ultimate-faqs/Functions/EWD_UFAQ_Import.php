<?php
// This function is used to import FAQ from another plugin

function EWD_UFAQ_Import(){
    global $wpdb;

    $Posts_Table_Name = $wpdb->prefix . "posts";
    $Sql = "SELECT ID FROM $Posts_Table_Name WHERE post_type='qa_faqs'";
    $Results = $wpdb->get_results($Sql);
    if (is_array($Results)){

        foreach($Results as $Result){
    
            $data_array = array('post_type' => 'ufaq');
            $where = array('ID' => $Result->ID);
            $wpdb->update($Posts_Table_Name, $data_array, $where);
        }
    }

    $Terms_Table_Name = $wpdb->prefix . "term_taxonomy";
    $data_array = array('taxonomy' => 'ufaq-category');
    $where = array('taxonomy' => 'faq_category');
    $wpdb->update($Terms_Table_Name, $data_array, $where);
    echo $wpdb->last_query;
}
?>