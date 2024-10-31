<?php
global $post, $myloyal_badge_rule;
$badges = \myLoyal\core\Options::instance()->get_option('badges', 'myloyal', [] );
$badge_settings = (new \myLoyal\core\Instance_Post( $post->ID ) )->get_meta( 'badge_settings' );
if ( !is_array( $badge_settings ) ) {
	$badge_settings = [];
    foreach ( $myloyal_badge_rule as $case => $settings_data ) {
	    $badge_settings[$case] = [];
    }
};
/*foreach ( $badges as $point_mark => $cat_data ) {
	foreach ( $cat_data as $cat => $badge_array ) {
		if ( in_array( $post->ID, $badge_array ) ) {
			$badge_settings = [
				'badge_id' => $post->ID,
				'point_mark' => $point_mark,
				'point_type' => 'point',
				''
			];
		}
	}
}*/
?>
<div id="myloyal-app">
    <template v-for="( settings_data, setting_case ) in myloyal_badge_rule">
        <br>
        <template v-for="(field, key) in settings_data.fields">
            <template v-if="!field.visible || ( field.visible && check_visibility_condition(badge_settings,field.visible) == true )">
                <template v-if="field.type == 'string'">
                            <span v-html="field.string">
                            </span>
                </template>
                <template v-if="field.type == 'select'">
                    <template v-if="field.multiple == true">
                        <select v-model="badge_settings[field.name]" multiple>
                            <option :value="value" v-for="(label, value) in field.options">{{ label }}</option>
                        </select>
                    </template>
                    <template v-else>
                        <select v-model="badge_settings[field.name]">
                            <option :value="value" v-for="(label, value) in field.options">{{ label }}</option>
                        </select>
                    </template>
                </template>
                <template v-if="field.type == 'number'">
                    <input type="number" v-model="badge_settings[field.name]"> {{ field.label }}
                </template>
            </template>
        </template>
    </template>
    <div class="mb-10 mt-10 fr">
        <a @click="save_rules" href="javascript:" class="btn btn-success">Save</a>
    </div>
</div>
<script>
	var badge_settings = JSON.parse('<?php echo json_encode( $badge_settings ); ?>');
	var post_id = '<?php echo $post->ID; ?>';

	var myloyal_badge_rule = JSON.parse('<?php echo json_encode( $myloyal_badge_rule ); ?>');
    myloyal_vuedata.data = Object.assign( myloyal_vuedata.data, {
		test: 'This is test',
		badge_settings: badge_settings,
		myloyal_badge_rule: myloyal_badge_rule
	});

    myloyal_vuedata.methods = Object.assign( myloyal_vuedata.methods, {
		check_visibility_condition: function (saved_rule,rule_condition) {
			var bool = true;
			for( var k in rule_condition ) {
				if ( saved_rule[rule_condition[k].key] !== rule_condition[k].val ) {
					bool = false;
					break;
				}
			}
			return bool;
		},
		save_rules: function () {
			var $ = jQuery;
			var _this = this;
			$.post(
				ajaxurl,
				{
					action: 'myloyal_save_badge_settings',
					badge_settings: _this.badge_settings,
                    post_id: post_id
				},
				function (res) {
					console.log(res);
				}
			)
		}
	});
</script>
<?php
/*$meta = [
        'actor' => 'roles', //all,role,user
        'actor_val' => [ '...' ],
        'target_point' => 100,
];*/