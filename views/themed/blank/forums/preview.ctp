<style type="text/css">
@font-face {
    font-family: 'ChantelliAntiquaRegular';
    src: url('/img/Chantelli_Antiqua-webfont.eot');
    src: url('/img/Chantelli_Antiqua-webfont.eot?#iefix') format('embedded-opentype'),
         url('/img/Chantelli_Antiqua-webfont.woff') format('woff'),
         url('/img/Chantelli_Antiqua-webfont.ttf') format('truetype'),
         url('/img/Chantelli_Antiqua-webfont.svg#ChantelliAntiquaRegular') format('svg');
    font-weight: normal;
    font-style: normal;

}

body {
	color: #aaa;
}

.gigantic {
	font-size: 110px;
	line-height: 120px;
	letter-spacing: -2px;
}

.huge, h1 {
	font: 42px/48px 'ChantelliAntiquaRegular', Cambria, Georgia, Times, 'Times New Roman', serif;
	letter-spacing: 0;
}

.large, h2 {
	font: 42px/48px 'ChantelliAntiquaRegular', Cambria, Georgia, Times, 'Times New Roman', serif;
	letter-spacing: 0;
}

.bigger, h3 {
	font: 26px/36px 'ChantelliAntiquaRegular', Cambria, Georgia, Times, 'Times New Roman', serif;
	letter-spacing: 0;
}

.big, h4 {
	font: 22px/32px 'ChantelliAntiquaRegular', Cambria, Georgia, Times, 'Times New Roman', serif;
	letter-spacing: 0;
}

.small, small {
	font-size: 13px;
	line-height: 18px;
}

/* Link colors */
a {color: rgb(92, 160, 197);text-decoration:none;}
a:hover{color: rgb(125, 207, 254); text-decoration: underline;}
a:visited{color: rgb(92, 160, 197);text-decoration:none;}
</style>
<?php echo $markitup->parse($content, $parser) ?> 