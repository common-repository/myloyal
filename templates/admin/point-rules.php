<?php
global $myloyal_point_rules;
$data = \myLoyal\core\Options::instance()->get_option('rule_data', 'myloyal', array() );
!is_array( $data ) ? $data = [] : '';
$formated_rules = [];
foreach ( $data as $case => $rule_sets ) {
    foreach ( $rule_sets as $r => $rule ) {
        $rule['case'] = $case;
        $formated_rules[] = $rule;
    }
}
?>
<div id="myloyal-app">
    <a href="javascript:" class="btn btn-ffffff clr-10B981 b-clr-10B981" @click="add_new_rule"
    ><?php _e('Add Rule','myloyal'); ?></a>
    <a href="javascript:" class="btn bg-10B981 b-clr-10B981" @click="save_rules"
    ><?php _e('Save Rules','myloyal'); ?></a>
    <a href="javascript:" class="btn btn-ffffff clr-10B981 b-clr-10B981" @click="expand_all"
    ><?php _e('Expand All','myloyal'); ?></a>

	<div class="mt-10">
        <div class="mb-10 bg-ffffff p-10 br-5 pt-20 pb-20" v-for="(saved_rule, rule_key ) in saved_rules">
            <a @click="remove_rule(saved_rule)" href="javascript:" class="btn btn-FE2424 fr">Remove Rule</a>

            <select v-model="saved_rule.case" class="w-100 mb-10 case_select" @change="on_change_save_rule(rule_key)">
                <option :value="rule_case" v-for="(point_rule, rule_case) in point_rules">{{ point_rule.label }}</option>
            </select>

            <div>
                <a href="javascript:" @click="expand_rule(rule_key)"
                v-if="expandables.indexOf(rule_key) == -1"
                >Expand</a>
                <a href="javascript:" @click="shrink_rule(rule_key)"
                   v-if="expandables.indexOf(rule_key) !== -1"
                >Shrink</a>
                <div v-if="expandables.indexOf(rule_key) !== -1">
                    <template v-for="(field, key) in point_rules[saved_rule.case].fields">
                        <template v-if="!field.visible || ( field.visible && check_visibility_condition(saved_rule,field.visible) == true )">
                            <template v-if="field.type == 'string'">
                                <span v-html="field.string">
                                </span>
                            </template>
                            <template v-if="field.type == 'select'">
                                <template v-if="field.multiple == true">
                                    <multiselect
                                            v-model="saved_rule[field.name]"
                                            :options="field.options"
                                            mode="tags"
                                    />
                                    <!--<select v-model="saved_rule[field.name]" multiple>
										<option :value="value" v-for="(label, value) in field.options">{{ label }}</option>
									</select>-->
                                </template>
                                <template v-else>
                                    <multiselect
                                            v-model="saved_rule[field.name]"
                                            :options="field.options"
                                    />
                                    <!--<select v-model="saved_rule[field.name]">
										<option :value="value" v-for="(label, value) in field.options">{{ label }}</option>
									</select>-->
                                </template>
                            </template>
                            <template v-if="field.type == 'number'">
                                <input type="number" v-model="saved_rule[field.name]"> {{ field.label }}
                            </template>
                        </template>
                    </template>
                </div>
            </div>
        </div>
	</div>
</div>
<style>
    [type='number'],[type='text']{
        height: 40px;
        border: 1px solid #ccc !important;
    }
    input, select,.multiselect{
        margin-bottom:10px;
    }
    .multiselect{
        display: inline-block;
         min-width: 200px;
        max-width: fit-content;
    }
    .multiselect-clear{
        position: absolute;
        right: 0;
        top: 10px;
    }
</style>
<script>
	var myloyal_point_rules = JSON.parse('<?php echo json_encode( $myloyal_point_rules ); ?>');
	console.log(myloyal_point_rules);
	myloyal_vuedata.data = Object.assign( myloyal_vuedata.data, {
		expandables: [],
		test_val: 'a',
		test_options: {
			a: 'A',
            b: 'B'
        },
		test: 'This is test',
		point_rules: myloyal_point_rules,
		saved_rules: JSON.parse('<?php echo json_encode($formated_rules); ?>')
	});
	myloyal_vuedata.methods = Object.assign( myloyal_vuedata.methods, {
		expand_all: function () {
			for ( var k in this.saved_rules ) {
				if ( this.expandables.indexOf(parseInt(k)) == -1 ) {
					this.expandables.push(parseInt(k));
                }
            }
            console.log(this.expandables);
		},
		expand_rule: function (rule_key) {
			if( this.expandables.indexOf(rule_key) == -1 ) {
				this.expandables.push(rule_key);
			}
			console.log(this.expandables);
		},
		shrink_rule: function (rule_key) {
			if( this.expandables.indexOf(rule_key) !== -1 ) {
				this.expandables.splice( this.expandables.indexOf( rule_key ), 1 );
			}
		},
		add_new_rule: function () {
			this.saved_rules.push({
                case: 'when_user_login'
            });
		},
		on_change_save_rule:function ( rule_key ) {
			this.expandables.splice( this.expandables.indexOf( rule_key ), 1 );
		    var _this = this;
			setTimeout(function () {
			    _this.expandables.push(rule_key);
		    },1);
        },
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
        remove_rule: function (saved_rule) {
            this.saved_rules.splice( this.saved_rules.indexOf(saved_rule), 1 );
        },
		save_rules: function () {
			var $ = jQuery;
			var _this = this;
			$.post(
				ajaxurl,
                {
                	action: 'myloyal_save_rule_data',
                    rule_data: _this.saved_rules
                },
                function (res) {
                    console.log(res);
                }
            )
		}
	});
</script>
