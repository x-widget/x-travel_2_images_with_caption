<?php
/*******
**This widget should use board skin x-board-travel-3 
**for product name and product price
********/


if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

widget_css();

$icon_url = widget_data_url( $widget_config['code'], 'icon' );

$file_headers = @get_headers($icon_url);

if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
    $icon_url = x::url()."/widget/".$widget_config['name']."/img/2paperswhite.png";
}

if( $widget_config['title'] ) $title = $widget_config['title'];
else $title = 'no title';

if( $widget_config['forum1'] ) $_bo_table = $widget_config['forum1'];
else $_bo_table = $widget_config['default_forum_id'];

if ( empty($_bo_table) ) jsAlert('Error: empty $_bo_table ? on widget :' . $widget_config['name']);

if( $widget_config['no'] ) $limit = $widget_config['no'];
else $limit = 6;

$list = g::posts( array(
			"bo_table" 	=>	$_bo_table,
			"limit"		=>	$limit,
			"select"	=>	"idx,domain,bo_table,wr_id,wr_parent,wr_is_comment,wr_comment,ca_name,wr_datetime,wr_hit,wr_good,wr_nogood,wr_name,mb_id,wr_subject,wr_content"
				)
		);
		
$title_query = "SELECT bo_subject FROM ".$g5['board_table']." WHERE bo_table = '".$_bo_table."'";
$title = cut_str(db::result( $title_query ),10,"...");

?>

<div class='travel_images_with_captions'>
		<div class='title'>
		<span class='travel-subject'>
		<img src='<?=$icon_url ?>'/>
		<a href='<?=G5_BBS_URL?>/board.php?bo_table=<?=$_bo_table?>'><?=$title?></a>
		</span>
		
		<a class='more_button' href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $_bo_table ?>">자세히</a>
		<div style='clear: both'></div>
		</div>

<?php	
	if ( $list ) {
	$count = 1;
		foreach( $list as $li ) {
?>
				<div class='travel_images_with_captions_container'>
					<div class='images_with_captions'>
						<div class='caption_image'>					
						<?						
							$imgsrc = x::post_thumbnail($_bo_table, $li['wr_id'], 161, 108);							
							if ( empty($imgsrc['src']) ) {
								$_wr_content = db::result("SELECT wr_content FROM $g5[write_prefix]$_bo_table WHERE wr_id='$li[wr_id]'");
								$image_from_tag = g::thumbnail_from_image_tag($_wr_content, $_bo_table, 161, 108);
								if ( empty($image_from_tag) ) $img = "<img src='".x::url()."/widget/".$widget_config['name']."/img/no_image.png'/>";
								else $img = "<img src='$image_from_tag'/>";
							} else $img = "<img src='$imgsrc[src]'/>";
						
							echo "<div class='img-wrapper'><a href='$li[url]'>".$img."</a></div>";
							
							$prod_name_prod_price_query = "SELECT wr_1, wr_2 FROM ".$g5['write_prefix'].$_bo_table." WHERE wr_id =".$li['wr_id'];
							$prod_item = db::row( $prod_name_prod_price_query );														
							
							if( $prod_item['wr_1'] ) $product_name = conv_subject( $prod_item['wr_1'], 20, '...' );
							else $product_name = "상품명을 입력해 주세요";
							
							if( $prod_item['wr_2'] ) $product_price = $prod_item['wr_2'];
							else $product_price = "상품가를 입력해 주세요";											
						?>
						</div>
						<div class='travel2-caption product_name'><a href='<?=$li['url']?>'><?=$product_name?></a></div>
						<div class='travel2-caption product_price'><a href='<?=$li['url']?>'><?=$product_price?></a></div>
						<div class='product_subject'><a href='<?=$li['url']?>'><?=conv_subject( $li['wr_subject'], 70, '...' )?></a></div>
					</div>
				</div>		
	<?
		$count++;
		}
	}
	else {
			for ( $i = 0; $i < 6; $i++ ) {?>
				<div class='travel_images_with_captions_container'>
					<div class='images_with_captions'>
						<div class='caption_image'>					
							<div class='img-wrapper'><a href='javascript:void(0)'><img src='<?=x::url()?>/widget/<?=$widget_config['name']?>/img/no_image.png' /></a></div>
						</div>
						<div class='travel2-caption product_name'><a href='javascript:void(0)'>상품명 입력</a></div>
						<div class='travel2-caption product_price'><a href='javascript:void(0)'>2,000페소</a></div>
						<div class='product_subject'><a href='javascript:void(0)'><center>글을 등록해 주세요</center></a></div>
					</div>
				</div>
			<?}?>
	<?}
		
?>
<div style='clear:both;'></div>
</div>