<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_categories extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Headers: content-type,Content-Type,X-Custom-Header, Upgrade-Insecure-Requests,Accept,x-requested-with");
		header('Content-Type: application/json');
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 60');
		header('Access-Control-Allow-Headers: AccountKey,x-requested-with, Content-Type, content-type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		$this->load->model('categories_model');
		$this->load->model('zipcodes_model');
		$this->load->model('products_model');

		error_reporting(0);
	}
	public function get_categories()
	{

		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		if (check_oauth_key($oauth_key)) {
			try {
				$data = array(
					'is_active' => $json_obj->is_active_only,
					'search_keyword' => $json_obj->search_keyword,
					'limit' => $json_obj->limit,
					'page_no' => $json_obj->page_no,
					'style' => $json_obj->style,
					'is_home_display' => $json_obj->is_home_display,
					'sort_column' => $json_obj->sort_column,
					'sort_order' => $json_obj->sort_order
				);
				//var_dump($json_obj->search_keyword);exit;
				if ($json_obj->search_keyword == "") {

					$result_val = $this->categories_model->get_categories($data);
					$category_backgrounds = ['category-1.png', 'category-2.png', 'category-3.png', 'category-4.png', 'category-5.png', 'category-6.png', 'category-7.png'];
					for ($i = 0; $i < count($result_val); $i++) {
						//$result=array();
						foreach ($result_val[$i] as $key => $value) {
							if ($key == "category_image") {
								$category_result[$key] = FILE_UPLOAD_PATH . 'category/' . $value;
							}
							if ($key == "category_background_image") {
								$category_result[$key] = FILE_UPLOAD_PATH . 'category/' . $category_backgrounds[$i];
							} else {
								$category_result[$key] = $value;
							}
						}
						$result[] = $category_result;
					}
				} else {
					$result_val = $this->categories_model->get_categories_by_search($data, $json_obj->search_keyword);
					for ($i = 0; $i < count($result_val); $i++) {
						//$result=array();
						foreach ($result_val[$i] as $key => $value) {
							if ($key == "category_image") {
								$category_result[$key] = FILE_UPLOAD_PATH . 'category/' . $value;
							} else {
								$category_result[$key] = $value;
							}
						}
						$result[] = $category_result;
					}
				}
				if (count($result_val) > 0) {
					$ArrData = $result;
					$success_message = '';
				} else {
					$errors = 'No Data Available';
				}
			} catch (Exception $e) {
				$ArrData = "There is problem";
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function add_category()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'category_name' => $json_obj->category_name,
				'category_slug' => $json_obj->category_slug,
				'category_description' => $json_obj->category_description,
				'category_image' => $json_obj->category_image,
				'category_background_color' => $json_obj->category_background_color,
				'category_icon' => $json_obj->category_icon,
				'is_home_display' => $json_obj->is_home_display,
				'is_perisible_products' => $json_obj->is_perisible_products,
				'parent_category_id' => $json_obj->parent_category_id,
				'style' => $json_obj->style,
				'created_by' => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);

			$result = $this->categories_model->add_category($data);
			$ArrData = $result;
			if ($result) {
				$success_message = 'Category added successfully';
			} else {
				$errors = 'Category Not Added Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_category_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'category_id' => $json_obj->category_id
			);
			$result = $this->categories_model->get_category_by_id($data);
			$ArrData = $result;
			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_category_by_slug()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'category_slug' => $json_obj->category_slug
			);
			$result = $this->categories_model->get_category_by_slug($data);
			$ArrData = $result;
			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function update_category()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$category_id = $json_obj->category_id;
			$data = array(
				'category_name' => $json_obj->category_name,
				'category_slug' => $json_obj->category_slug,
				'category_description' => $json_obj->category_description,
				'category_image' => $json_obj->category_image,
				'category_background_color' => $json_obj->category_background_color,
				'category_icon' => $json_obj->category_icon,
				'is_home_display' => $json_obj->is_home_display,
				'is_perisible_products' => $json_obj->is_perisible_products,
				'parent_category_id' => $json_obj->parent_category_id,
				'style' => $json_obj->style,
				'created_by' => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);
			$result = $this->categories_model->update_category($data, $category_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'Category Updated Successfully';
			} else {
				$errors = 'Category Not Updated Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function delete_category()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$category_id = $json_obj->category_id;
			$data = array(
				'is_active' => '0',
				'is_deleted' => '1'
			);
			$result = $this->categories_model->update_category($data, $category_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'Category Deleted Successfully';
			} else {
				$errors = 'Category Not Deleted Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_home_categories_product()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$result_val = $result = $ArrData = array();
		$category_result = array();
		$data['is_perisible_products'] = "";
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
				'style' => $json_obj->style,
				'is_home_display' => $json_obj->is_home_display,
				'sort_column' => $json_obj->sort_column,
				'sort_order' => $json_obj->sort_order,
				'zipcode' => $json_obj->zipcode,
			);
			if ($data['zipcode'] != "") {
				$result = $this->zipcodes_model->get_zipcode_by_data($data['zipcode']);
				if (count($result) > 0) {
					
					$data['is_perisible_zipcode'] = $result[0]->can_deliver_perishable_products;
					$data['is_liker_zipcode'] = $result[0]->can_deliver_liker_products;
					$data['is_cook_food_zipcode'] = $result[0]->can_deliver_cook_food_products;

					$result_val = $this->categories_model->get_categories($data);

				} else {
					$result_val = $this->categories_model->get_categories($data);
				}
			} else {
				$result_val = $this->categories_model->get_categories($data);
			}
			if (is_array($result_val) && count($result_val) > 0) {
				for ($i = 0; $i < count($result_val); $i++) {
					foreach ($result_val[$i] as $key => $value) {
						if ($key == "category_image") {
							$category_result[$key] = FILE_UPLOAD_PATH . 'category/' . $value;
						} else if ($key == "category_background_image") {
							$category_result[$key] = FILE_UPLOAD_PATH . 'category/' . $value;
						} else {
							$category_result[$key] = $value;
						}
					}
					$result_category[] = $category_result;
					$result_category_ids[] = $result_category[$i]['category_id'];
				}
				$result_product = $this->categories_model->get_product_by_category_id(implode(",", $result_category_ids));

				//$result["categories"]=$result_category;
				$temp_result_array = array();
				foreach ($result_category as $categories) {
					$temp_result_array[] = $categories;
					//$temp_result_array[$categories['category_id'] -1 ]['products']['products'] = $result_product;
				}
				// foreach($result_product as $products)
				// {
				// 	$result_variants =$this->categories_model->get_variant_by_product_id($products->product_id);
				// 	$temp_result_array[$categories['category_id'] -1 ]['products']['variants'][] = $result_variants;
				// }
				$result['categories'] = $temp_result_array;
			}

			$ArrData = $result_category;
			if (is_array($ArrData) && count($ArrData) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = $result;
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_category_product_detail()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$zipcode = $json_obj->zipcode;
		$errors = $success_message = '';
		$ArrData = array();
		$category_result = "";
		$zipcodeData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'category_slug' => $json_obj->category_slug
			);

			if($zipcode != ""){
				$zipcodeData = $this->zipcodes_model->get_zipcode_by_data($zipcode);
				if (count($zipcodeData) > 0) {
					$data['is_perisible_zipcode'] = $zipcodeData[0]->can_deliver_perishable_products;
					$data['is_liker_zipcode'] = $zipcodeData[0]->can_deliver_liker_products;
					$data['is_cook_food_zipcode'] = $zipcodeData[0]->can_deliver_cook_food_products;
				}
			}
			$category_result = $this->categories_model->get_category_by_slug($data);
			if(!empty($category_result)){
				
				$category_id = $category_result[0]->category_id;
				$category_is_perisible_products = $category_result[0]->is_perisible_products;
				$category_is_liker_category = $category_result[0]->is_liker_category;
				$category_is_cook_food_category = $category_result[0]->is_cook_food_category;
				
				$temp_result = $this->categories_model->get_product_by_category_id($category_result[0]->category_id);
				$result["category"] = $category_result[0];
				$ArrFinal = array();
				$i = 0;
				$prev_product_id = 0;
				$ArrFilter = array();
				$ArrNum = 0;

				for ($p = 0; $p < count($temp_result); $p++) {
					
					$ProductValid = 1;
					if(isset($zipcodeData[0]->can_deliver_perishable_products) && $zipcodeData[0]->can_deliver_perishable_products == "No")
					{
						if($category_is_perisible_products == '2' && $temp_result[$p]->is_perisible_products != '1'){
							$ProductValid = 0;
						}
					}
					if($ProductValid == 1 && isset($zipcodeData[0]->can_deliver_liker_products) && $zipcodeData[0]->can_deliver_liker_products == "No")
					{
						if($category_is_liker_category == '2' && $temp_result[$p]->is_liker_products != '1'){
							$ProductValid = 0;
						}
					}
					if($ProductValid == 1 && isset($zipcodeData[0]->can_deliver_cook_food_products) && $zipcodeData[0]->can_deliver_cook_food_products == "No")
					{
						if($category_is_cook_food_category == '2' && $temp_result[$p]->is_liker_products != '1'){
							$ProductValid = 0;
						}
					}

					if($ProductValid == 1) {
						$ArrFilter[$ArrNum] = $temp_result[$p];
						$ArrNum++;
					}

				}
				$temp_result = $ArrFilter;
				if(!empty($temp_result)){
					foreach ($temp_result as $arr) {

						if ($prev_product_id != $arr->product_id) {

							$ArrFinal[$i] = $arr;

							$tempArray = array();
							$previous_variant_id = "";
							foreach ($temp_result as $arr1) {

								if ($arr->product_id == $arr1->product_id && $previous_variant_id != $arr1->id) {
									$t = array();
									if ($arr1->id > 0) {
										$t['size'] = $arr1->product_variant_size;
										$t['price'] = $arr1->variant_price;
										$t['variant_id'] = $arr1->id;
										$t['is_out_of_stock'] = $arr1->varaint_is_out_of_stock;
									}
									$tempArray[] = $t;
									$previous_variant_id = $arr1->id;
								}
							}
                            // usort($tempArray, function($a, $b) {
                            // 	return $a['size'] <=> $b['size'];
                            // });

							$unique = array_map("unserialize", array_unique(array_map("serialize", $tempArray)));
							usort($unique, function($a, $b) {
								return $a['price'] - $b['price']; // Ascending sort by 'price'
							});
							

							$ArrFinal[$i] = $arr;

							$ArrFinal[$i]->product_size = $unique;

							$i++;
						}

						$prev_product_id = $arr->product_id;
					}
					$variants = array();
					foreach ($temp_result as $arr) {
						$temp_variant_result = $this->products_model->get_variant_by_product_id($arr->product_id);

						// print_r($temp_variant_result);


						for ($i = 0; $i < count($temp_variant_result); $i++) {
							$variants[] = $temp_variant_result[$i];
						}

						$result['variants'] = $variants;
					}

					$product_result = $ArrFinal;

					for ($i = 0; $i < count($product_result); $i++) {
						//$result=array();
						foreach ($product_result[$i] as $key => $value) {
							if ($key == "product_image") {
								$products[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
							} else {
								$products[$key] = $value;
							}

							$p_sale_price = $product_result[$i]->sale_price;
							$p_prod_price = $product_result[$i]->product_price;
							$cal_tag_dicount = $this->products_model->calculate_tag_discount($p_prod_price,$p_sale_price);
							if(!empty($cal_tag_dicount)){
								$products['tag_discount'] = $cal_tag_dicount.'% OFF';
							}else{
								$products['tag_discount'] = '';
							}
						}
						$result['products'][] = $products;
					}

					//$filter_result=$this->categories_model->get_category_filter($product_result[0]->product_id);
					foreach ($product_result as $products) {
						$product_id[] = $products->product_id;
					}
					$result['product_id'] = $product_id;
					//$ArrData = $result;
					if (count($result) > 0) {
						$ArrData = $result;
						$success_message = '';
					} else {
						$errors = 'No data available';
					}
					// echo "<pre> "; print_r($result);exit;
				} else {
					$ArrData = array();
					$success_message = '';
					$errors = 'No data available';
				}
			} else {
				$ArrData = array();
				$success_message = '';
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_category_product_detail_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		// print_r($json_obj);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$category_result = "";
		$result = array();

		$category_result = $this->categories_model->get_category_by_slug(['category_slug'=>$json_obj->category_slug]);
		$category_id_url = $category_result[0]->category_id;
		// echo 'category_id_url: '.$category_id_url;
		$category_id_arr = $json_obj->category_id;
		if(!empty($category_id_url)){
			if(!in_array($category_id_url, $category_id_arr)){
				$category_id_arr[] = $category_id_url;
			}
			
		}
		$category_id = implode(",", $category_id_arr);

		if (check_oauth_key($oauth_key)) {
			
			$data = array(
				'category_id' => $category_id,
				// 'category_id' => implode(",", $json_obj->category_id),
				'brand_id' => implode(",", $json_obj->brand_id),
				'tag_id' => implode(",", $json_obj->tag_id),
				'min_price' => $json_obj->min_price,
				'max_price' => $json_obj->max_price,
				'search_keyword' => $json_obj->search_keyword,
			);

			if($json_obj->zipcode != ""){
				$zipcodeData = $this->zipcodes_model->get_zipcode_by_data($json_obj->zipcode);
				if (count($zipcodeData) > 0) {
					$data['can_deliver_perishable_products'] = $zipcodeData[0]->can_deliver_perishable_products;
					$data['can_deliver_liker_products'] = $zipcodeData[0]->can_deliver_liker_products;
					$data['can_deliver_cook_food_products'] = $zipcodeData[0]->can_deliver_cook_food_products;
				}
			}

			$temp_result = $this->categories_model->get_filter_products($data);
			if (count($temp_result) > 0) {
				$ArrFinal = array();
				$i = 0;
				$prev_product_id = 0;
				foreach ($temp_result as $arr) {

					if ($prev_product_id != $arr->product_id) {

						$ArrFinal[$i] = $arr;

						$tempArray = array();

						foreach ($temp_result as $arr1) {
							if ($arr->product_id == $arr1->product_id) {
								$prod_var_arr = $this->products_model->get_variant_by_product_id($arr1->product_id);
								if(count($prod_var_arr) > 0){
									foreach($prod_var_arr as $key => $val){
										$t = array();
											$t['size'] = $val->product_variant_size;
											$t['price'] = $val->variant_price;
											$t['variant_id'] = $val->id;
											// $t['is_out_of_stock'] = $arr1->varaint_is_out_of_stock;
										$tempArray[] = $t;
									}
								}
								
							}
						}

						$ArrFinal[$i] = $arr;

						$unique = array_map("unserialize", array_unique(array_map("serialize", $tempArray)));
						usort($unique, function($a, $b) {
							return $a['price'] - $b['price']; // Ascending sort by 'price'
						});

						$ArrFinal[$i]->product_size = $unique;

						$i++;
					}

					$prev_product_id = $arr->product_id;
				}
				$product_result = $ArrFinal;

				//$result["category"]=$category_result[0];
				for ($i = 0; $i < count($product_result); $i++) {
					//$result=array();
					foreach ($product_result[$i] as $key => $value) {
						if ($key == "product_image") {
							$products[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
						} else {
							$products[$key] = $value;
						}

						$p_sale_price = $product_result[$i]->sale_price;
						$p_prod_price = $product_result[$i]->product_price;
						$cal_tag_dicount = $this->products_model->calculate_tag_discount($p_prod_price,$p_sale_price);
						if(!empty($cal_tag_dicount)){
							$products['tag_discount'] = $cal_tag_dicount.'% OFF';
						}else{
							$products['tag_discount'] = '';
						}
					}
					$result['product_details'][] = $products;
				}
			}
			//$product_id =array();
			//$filter_result=$this->categories_model->get_category_filter($product_result[0]->product_id);
			//  foreach($product_result as $products)
			//  {
			//   	$product_id[]=$products->product_id;
			//  }
			//  if($product_id != "" || $product_id != null)
			// 	$result['products']=$product_id;
			// else
			// 	$result['products']="";

			//$ArrData = $result;
			if (is_array($result) && count($result) > 0 && $result != null) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_filters()
	{

		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$category_result = "";
		$zipcodeData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'product_ids' => $json_obj->product_ids,
				'zipcode' => $json_obj->zipcode
			);

			$product_ids = implode(',', $data['product_ids']);
			$filter_result = $this->categories_model->get_category_filter($product_ids);

			$filter1 = str_replace('"{\"', '{"', $filter_result[0]->category);
			$filter1 = str_replace('\"}"', '"}', $filter1);
			$filter1 = str_replace('\"', '"', $filter1);

			$filter2 = str_replace('"{\"', '{"', $filter_result[0]->brand);
			$filter2 = str_replace('\"}"', '"}', $filter2);
			$filter2 = str_replace('\"', '"', $filter2);

			$filter3 = str_replace('"{\"', '{"', $filter_result[0]->tag);
			$filter3 = str_replace('\"}"', '"}', $filter3);
			$filter3 = str_replace('\"', '"', $filter3);
			// 1. decode jsonstring for category to a temporary array
			$category_filters = json_decode($filter1);
			$brand_filters = json_decode($filter2);
			$tag_filters = json_decode($filter3);

			// 2. define a final array for category ar_final_category
			$arr_final_category = array();

			// 3. For loop for all categories. 
			// 3.1 check if ar_final_category has the individual category or not (in_array can be used)
			// 3.2 
			$check_extra_cond = 0;
			if ($json_obj->zipcode != "") {
				
				$zipcodeData = $this->zipcodes_model->get_zipcode_by_data($json_obj->zipcode);
				if (count($zipcodeData) > 0) {
					
					$Z_perisible = $zipcodeData[0]->can_deliver_perishable_products;
					$Z_liker = $zipcodeData[0]->can_deliver_liker_products;
					$Z_cook_food = $zipcodeData[0]->can_deliver_cook_food_products;

					if($Z_perisible == "No" || $Z_liker == "No" || $Z_cook_food == "No"){
						$check_extra_cond = 1;
					}
				}
			}
			if (is_array($category_filters) && count($category_filters) > 0 || $category_filters != "" || $category_filters != null) {
				for ($i = 0; $i < count($category_filters); $i++) {
					if($check_extra_cond == 1){
						$check_category_id = $category_filters[$i]->category_id;
						$newData = array(
							'category_id' => $check_category_id
						);
						$categoryDetails = $this->categories_model->get_category_by_id($newData);
						if(!empty($categoryDetails)){
							$CatAdd = 1;
							if($Z_perisible == "No" && $categoryDetails[0]->is_perisible_category == 0){
								$CatAdd = 0;
							}
							if($CatAdd == 1){
								$arr_final_category['category'][$category_filters[$i]->category_id] = $category_filters[$i]->category_name;
							}
						}
					} else {
						$arr_final_category['category'][$category_filters[$i]->category_id] = $category_filters[$i]->category_name;
					}
					
				}
			} else {
				$arr_final_category['category'] = "";
			}
			if (is_array($brand_filters) && count($brand_filters) > 0 || $brand_filters != "" || $brand_filters != null) {
				for ($j = 0; $j < count($brand_filters); $j++) {
					$arr_final_category['brand'][$brand_filters[$j]->brand_id] = $brand_filters[$j]->brand_name;
				}
			} else {
				$arr_final_category['brand'] = "";
			}
			if (is_array($tag_filters) && count($tag_filters) > 0 || $tag_filters != "" || $tag_filters != null) {
				for ($k = 0; $k < count($tag_filters); $k++) {
					$arr_final_category['tag'][$tag_filters[$k]->tag_id] = $tag_filters[$k]->tag;
				}
			} else {
				$arr_final_category['tag'] = "";
			}
			// 4. Do same process for other things. tags & brand.


			$result['filters'] = $arr_final_category;

			//$ArrData = $result;
			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_category_product_search()
	{

		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$category_result = "";
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'search_term' => $json_obj->search_term,
				'zipcode' => $json_obj->zipcode
			);
			if ($data['zipcode'] != "") {
				$zipcode_result = $this->zipcodes_model->get_zipcode_by_data($data['zipcode']);
				$data['is_perisible_products'] = $zipcode_result[0]->can_deliver_perishable_products;
				if (count($zipcode_result) > 0) {
					if ($zipcode_result[0]->can_deliver_perishable_products != "" && $zipcode_result[0]->can_deliver_perishable_products == "No") {
						$data['is_perisible_products'] = "0";
						$temp_result = $this->categories_model->get_product_by_category_search($data);
						$result["category"] = $category_result[0];
					} else {
						$temp_result = $this->categories_model->get_product_by_category_search($data);
						$result["category"] = $category_result[0];
					}
				} else {
					$temp_result = array();
				}
			} else {

				$temp_result = $this->categories_model->get_product_by_category_search($data);
				$result["category"] = $category_result[0];
			}
		
			if (is_array($temp_result) && count($temp_result) > 0) {
				array_multisort(array_column($temp_result, 'product_id'), SORT_ASC, $temp_result);
				$ArrFinal = array();
				$i = 0;
				$prev_product_id = 0;

				foreach ($temp_result as $arr) {

					if ($prev_product_id != $arr->product_id) {

						$ArrFinal[$i] = $arr;

						$tempArray = array();

						foreach ($temp_result as $arr1) {

							if ($arr->product_id == $arr1->product_id) {
								$t = array();
								if ($arr1->id > 0) {
									$t['size'] = $arr1->product_variant_size;
									$t['price'] = $arr1->variant_price;
									$t['variant_id'] = $arr1->id;
									$t['is_out_of_stock'] = $arr1->varaint_is_out_of_stock;
								}
								$tempArray[] = $t;
							}
						}

						$ArrFinal[$i] = $arr;

						$unique = array_map("unserialize", array_unique(array_map("serialize", $tempArray)));
						usort($unique, function($a, $b) {
							return $a['price'] - $b['price']; // Ascending sort by 'price'
						});
						$ArrFinal[$i]->product_size = $unique;

						$i++;
					}

					$prev_product_id = $arr->product_id;
				}
				$product_result = $ArrFinal;
				for ($i = 0; $i < count($product_result); $i++) {
					//$result=array();
					foreach ($product_result[$i] as $key => $value) {
						if ($key == "product_image") {
							$products[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
						} else {
							$products[$key] = $value;
						}
					}
					$result['products'][] = $products;
				}

				//$filter_result=$this->categories_model->get_category_filter($product_result[0]->product_id);
				foreach ($product_result as $products) {
					$product_id[] = $products->product_id;
				}
				$result['product_id'] = $product_id;
			} else {
				$result = "";
				$errors = 'No data available';
			}
			if (is_array($result) && count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function get_header_product_search()
	{
		
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$category_result = "";
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'search_term' => $json_obj->search_keyword,
				'zipcode' => $json_obj->zipcode,
				'limit' => 10,
			);
			if ($data['zipcode'] != "") {
				$zipcodeData = $this->zipcodes_model->get_zipcode_by_data($data['zipcode']);
				if (count($zipcodeData) > 0) {
					
					$data['can_deliver_perishable_products'] = $zipcodeData[0]->can_deliver_perishable_products;
					$data['can_deliver_liker_products'] = $zipcodeData[0]->can_deliver_liker_products;
					$data['can_deliver_cook_food_products'] = $zipcodeData[0]->can_deliver_cook_food_products;

					$temp_result = $this->categories_model->get_product_by_category_search($data);
					$result["category"] = $category_result[0];
					

				} else {
					$temp_result = $this->categories_model->get_product_by_category_search($data);
					$result["category"] = $category_result[0];
				}
			} else {

				$temp_result = $this->categories_model->get_product_by_category_search($data);
				$result["category"] = $category_result[0];
			}
			if (is_array($temp_result) && count($temp_result) > 0) {
				//array_multisort(array_column($temp_result, 'product_id'), SORT_ASC, $temp_result);
				$ArrFinal = array();
				$i = 0;
				$prev_product_id = 0;

				foreach ($temp_result as $arr) {

					if($i==10){
						break;
					}

					if ($prev_product_id != $arr->product_id) {
						$ArrFinal[$i] = $arr;
						$tempArray = array();

						foreach ($temp_result as $arr1) {

							if ($arr->product_id == $arr1->product_id) {
								$t = array();
								if ($arr1->id > 0) {
									$t['size'] = $arr1->product_variant_size;
									$t['price'] = $arr1->variant_price;
									$t['variant_id'] = $arr1->id;
									$t['is_out_of_stock'] = $arr1->varaint_is_out_of_stock;
								}
								$tempArray[] = $t;
							}
						}

						$ArrFinal[$i] = $arr;
						$ArrFinal[$i]->product_size = $tempArray;
						$i++;
					}

					$prev_product_id = $arr->product_id;
				}
				$product_result = $ArrFinal;
				for ($i = 0; $i < count($product_result); $i++) {
					//$result=array();
					foreach ($product_result[$i] as $key => $value) {
						if ($key == "product_image") {
							$products[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
						} else {
							$products[$key] = $value;
						}
					}
					$result['products'][] = $products;
				}

				//$filter_result=$this->categories_model->get_category_filter($product_result[0]->product_id);
				foreach ($product_result as $products) {
					$product_id[] = $products->product_id;
				}
				$result['product_id'] = $product_id;
			} else {
				$result = "";
				$errors = 'No data available';
			}
			if (is_array($result) && count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
}