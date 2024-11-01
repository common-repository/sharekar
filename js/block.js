(function(blocks, element, blockEditor, components, data){


const { InnerBlocks, RichText, InspectorControls } = blockEditor;
const { withSelect } = data;
const { Fragment, createElement } = element;
const { PanelBody, ColorPicker } = components;
const { __ } = wp.i18n;
const e = createElement;

//Register Block
blocks.registerBlockType('sharekar/clicktotweet', {
	title: 'Click to Tweet',
	icon: 'twitter',
	keywords: ['Twitter', 'Tweet', 'Sharekar'],
	attributes: {
		tweet: {
			type: 'string'
		},
		bg_color: {
			type: 'string',
			default: '#1da1f2'
		},
		color: {
			type: 'string',
			default: '#fff'
		},
	},
	edit: withSelect(function(select) {
		
		return {
			permalink: select('core/editor').getPermalink()
		};
		})(function(props) {

		let current_rem_char;
		console.log(props.permalink);
		const twitter_background = {
			backgroundColor: '#1da1f2',
			color: '#fff',
			padding: '20px',
		};

		return e(Fragment, {},
			
			// settings block
			e(InspectorControls, {},
				e(PanelBody, { title: __('Background Color', 'sharekar')},
					e(ColorPicker, {
						label: __('Background Color', 'sharekar'),
						onChangeComplete: (value) => {
							if(value.source == 'hex' && value.hex == '') {
								props.setAttributes({ bg_color: '' });
							}
							else {
								props.setAttributes({ bg_color: value.hex });
							}
						},
						color: props.attributes.bg_color && props.attributes.bg_color  !== '' ? props.attributes.bg_color : twitter_background.backgroundColor,
						disableAlpha: true,
					})
				),
				e(PanelBody, { title: __('Text Color', 'sharekar')},
					e(ColorPicker, {
						label: __('Text Color', 'sharekar'),
						onChangeComplete: (value) => {
							if(value.source == 'hex' && value.hex == '') {
								props.setAttributes({ color: '' });
							}
							else {
								props.setAttributes({ color: value.hex });
							}
						},
						color: props.attributes.color && props.attributes.color  !== '' ? props.attributes.color : twitter_background.color,
						disableAlpha: true,
					}),
				)
			),
			e('div', 
				{
					style: {
						backgroundColor: (() => {
							return props.attributes.bg_color && props.attributes.bg_color !== '' ? props.attributes.bg_color : twitter_background.backgroundColor;
						})(),
						color: (() => {
							return props.attributes.color && props.attributes.color !== '' ? props.attributes.color : twitter_background.color;
						})()
					}
				},
				//editable tweet
				e('div', {style:{
					padding: '20px',
					margin: '10px 0'
				}},
					e(RichText, {
						format: 'string',
						onChange: (value) => {
							props.setAttributes({tweet: value});
						},
						value: props.attributes.tweet,
						allowedFormats: []
					})
				),
				e('div', {
					style:{
						display: 'flex',
						justifyContent: 'space-between',
						alignItems: 'center',
						padding: '20px',
						fontSize: '16px',
					}
				},
					e('span', null, 
						(() => {
							let max_tweet_char = 280,
							tweet_len = props.attributes.tweet ? props.attributes.tweet.length : 0;
							current_rem_char = max_tweet_char - (props.permalink.length + tweet_len);
						})(),
						e('span', null, 
							(() => {
								return __('Remaining Characters: ', 'sharekar') +  current_rem_char;
							})()
						)
					),
					e('span', {
						style: {
							display:'flex',
							alignItems: 'center',
							fontWeight: '400',
						}
					}, 
						e('span', {
							className: 'dashicons dashicons-twitter'
						}, null),
						e('span', {
							style :{
								marginLeft: '3px'
							}
						}, __('Click to Tweet', 'sharekar'))
					),
				),
			)
		);
	}),
	save: function(props) {
		return null;
	}
});

})(wp.blocks, wp.element, wp.blockEditor, wp.components, wp.data);