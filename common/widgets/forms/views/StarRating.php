<style>
		@font-face {
			font-family: 'Material Icons';
			font-style: normal;
			font-weight: 400;
			src: local('Material Icons'), local('MaterialIcons-Regular'), url(https://fonts.gstatic.com/s/materialicons/v7/2fcrYFNaTjcS6g4U3t-Y5UEw0lE80llgEseQY3FEmqw.woff2) format('woff2'), url(https://fonts.gstatic.com/s/materialicons/v7/2fcrYFNaTjcS6g4U3t-Y5RV6cRhDpPC5P4GCEJpqGoc.woff) format('woff');
		}
		.material-icons {
			font-family: 'Material Icons';
			font-weight: normal;
			font-style: normal;
			font-size: 24px;
			line-height: 1;
			letter-spacing: normal;
			text-transform: none;
			display: inline-block;
			word-wrap: normal;
			-moz-font-feature-settings: 'liga';
			-moz-osx-font-smoothing: grayscale;
		}
		i {
			cursor :  pointer;
		}
</style>
<?php
echo $form->field($model, $field)->hiddenInput()->label($label);
$fieldId=strtolower(preg_replace("/^.*\\\/","",$model->className()))."-".strtolower($field);
?>
<span id="rating"></span>
<?php
$script="
$('#rating').addRating({fieldId:'".$fieldId."',selectedRatings:".ceil($model->$field)."});
";
//            max: 5,
//            half: true,
//            fieldName: 'rating',
//            fieldId: 'rating',
//            icon: 'star',
//            selectedRatings:0
$this->registerJs($script);

$this->registerJsFile("/js/jquery.star.rating.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);
?>