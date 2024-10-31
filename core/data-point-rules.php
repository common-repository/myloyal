<?php
global $myloyal_point_rules, $myloyal_badge_rule, $myloyal_case_hooks;
$all_roles = get_editable_roles();
$roles = [];
foreach ( $all_roles as $role_name => $role_array ) {
	$roles[$role_name] = $role_array['name'];
}

$common_fields = [
	[
		'name' => 'action',
		'type' => 'select',
		'label' => 'Action',
		'options' => [
			'add' => 'Add',
			'deduct' => 'Deduct'
		],
		'default' => 'add',
		'desc' => '',
	],
	[
		'name' => 'point',
		'type' => 'number',
		'label' => 'Point',
		'default' => 1,
		'desc' => ''
	],
	[
		'type' => 'string',
		'string' => __( 'to', 'myloyal' )
	],
	[
		'name' => 'target_actor',
		'type' => 'select',
		'label' => '',
		'options' => [
			'own' => __( 'Who perform this', 'myloyal'),
		],
		'default' => 'own'
	],
	[
		'name' => 'target_actor-roles',
		'type' => 'select',
		'label' => 'Selected Roles',
		'desc' => 'Roles with users to who this rule will be applied',
		'options' => [
			'role1' => 'Role1'
		],
		'visible' => [
			[
				'key' => 'target_actor',
				'val' => 'roles'
			]
		],
		'default' => ''
	],
	[
		'name' => 'target_actor-users',
		'type' => 'select',
		'label' => 'Selected Users',
		'desc' => 'Users to who this rule will be applied',
		'options' => [
			'user1' => 'User1'
		],
		'visible' => [
			[
				'key' => 'target_actor',
				'val' => 'users'
			]
		],
		'default' => ''
	],
	//
	[
		'type' => 'string',
		'string' => __( 'With Exception for', 'myloyal' )
	],
	[
		'name' => 'exception_for',
		'type' => 'select',
		'label' => '',
		'desc' => '',
		'options' => [
			'no' => __( 'No one', 'myloyal' ),
			'roles' => __( 'Following Roles', 'myloyal' ),
			'users' => __( 'Following Users', 'myloyal' )
		],
		'default' => 'no'
	],
	[
		'name' => 'exception_for-roles',
		'type' => 'select',
		'label' => 'Selected Roles',
		'desc' => 'Roles with users to who this rule will NOT be applied',
		'options' => [
			'role1' => 'Role1'
		],
		'visible' => [
			[
				'key' => 'exception_for',
				'val' => 'roles'
			]
		],
		'default' => ''
	],
	[
		'name' => 'exception_for-users',
		'type' => 'select',
		'label' => 'Selected Users',
		'desc' => 'Users to who this rule will NOT be applied',
		'options' => [
			'user1' => 'User1'
		],
		'visible' => [
			[
				'key' => 'exception_for',
				'val' => 'users'
			]
		],
		'default' => ''
	],
	[
		'type' => 'string',
		'string' => '<div>Apply this rule for</div>'
	],
	[
		'name' => 'applicable_for',
		'type' => 'select',
		'label' => '',
		'options' => [
			'all' => __( 'All Users', 'myloyal' ),
			'roles' => __( 'Following Roles', 'myloyal' ),
			'users' => __( 'Specific Users', 'myloyal' )
		],
		'default' => 'all'
	],
	[
		'name' => 'applicable_for-roles',
		'type' => 'select',
		'label' => 'Selected Roles',
		'multiple' => true,
		'desc' => 'Roles with users to who this rule will be applied',
		'options' => [
			'role1' => 'Role1'
		],
		'visible' => [
			[
				'key' => 'applicable_for',
				'val' => 'roles'
			]
		],
		'default' => ''
	],
	[
		'name' => 'applicable_for-users',
		'type' => 'select',
		'label' => 'Selected Users',
		'desc' => 'Users to who this rule will be applied',
		'options' => [
			'user1' => 'User1'
		],
		'visible' => [
			[
				'key' => 'applicable_for',
				'val' => 'users'
			]
		],
		'default' => ''
	],

];
$myloyal_point_rules =  [
	'when_user_visit_site' => [
		'hook' => $myloyal_case_hooks['when_user_visit_site'],
		'label' => __( 'When user visit site', 'myloyal' ),
		'fields' => [
			[
				'type' => 'string',
				'string' => ' Through '
			],
			[
				'name' => 'visitable_page',
				'type' => 'select',
				'options' => [
					'all' => __( 'any', 'myloyal' ),
					'sel_page' => __( 'following pages', 'myloyal' )
				]
			],
			[
				'name' => 'sel_page',
				'type' => 'select',
				'options' => [
					'1' => __( 'page1', 'myloyal' ),
					'2' => __( 'page1', 'myloyal' ),
				],
				'multiple' => true,
				'visible' => [
					[
						'key' => 'visitable_page',
						'val' => 'sel_page'
					]
				],
			],
			[
				'type' => 'string',
				'string' => ' page and if the user is '
			],
			[
				'name' => 'user_type',
				'type' => 'select',
				'options' => [
					'guest' => __( 'guest', 'myloyal' ),
					'logged_in' => __( 'logged in', 'myloyal' )
				]
			],
			[
				'type' => 'string',
				'string' => ' user '
			],
			[
				'name' => 'role_constrain_type',
				'type' => 'select',
				'options' => [
					'include' => __( 'with', 'myloyal' ),
					'logged_in' => __( 'not with', 'myloyal' )
				],
				'visible' => [
					[
						'key' => 'user_type',
						'val' => 'logged_in'
					]
				],
			],
			[
				'name' => 'user_role',
				'type' => 'select',
				'multiple' => true,
				'options' => [
					'all' => __( 'Any', 'myloyal' ),
					'role1' => __( 'Role1', 'myloyal' ),
					'role2' => __( 'Role2', 'myloyal' )
				],
				'visible' => [
					[
						'key' => 'user_type',
						'val' => 'logged_in'
					]
				],
			],
			[
				'type' => 'string',
				'string' => 'Then'
			],
			[
				'name' => 'action',
				'type' => 'select',
				'label' => 'Action',
				'options' => [
					'add' => 'Add',
					'deduct' => 'Deduct'
				],
				'default' => 'add',
				'desc' => '',
			],
			[
				'name' => 'point',
				'type' => 'number',
				'label' => 'Point',
				'default' => 1,
				'desc' => ''
			],
			[
				'type' => 'string',
				'string' => __( 'to', 'myloyal' )
			],
			[
				'name' => 'target_actor',
				'type' => 'select',
				'label' => '',
				'options' => [
					'own' => __( 'him', 'myloyal'),
				],
				'default' => 'own'
			],
		]
	],
	'when_user_register' => [
		'hook' => $myloyal_case_hooks['when_user_register'],
		'label' => __( 'When user registers', 'myloyal' ),
		'fields' => [
			[
				'type' => 'string',
				'string' => __( ' as ')
			],
			[
				'name' => 'role',
				'type' => 'select',
				'multiple' => true,
				'options' => array_merge( ['all' => __( 'any', 'myloyal' )], $roles )
			],
			[
				'type' => 'string',
				'string' => __( ' role(s), Then ','myloyal' )
			],
			[
				'name' => 'action',
				'type' => 'select',
				'label' => 'Action',
				'options' => [
					'add' => 'Add',
					'deduct' => 'Deduct'
				],
				'default' => 'add',
				'desc' => '',
			],
			[
				'name' => 'point',
				'type' => 'number',
				'label' => 'Point',
				'default' => 1,
				'desc' => ''
			],
			[
				'type' => 'string',
				'string' => __( 'to', 'myloyal' )
			],
			[
				'name' => 'target_actor',
				'type' => 'select',
				'label' => '',
				'options' => [
					'own' => __( 'him', 'myloyal'),
				],
				'default' => 'own'
			],
		]
	],
	'when_user_gets_comment' => [
		'hook' => $myloyal_case_hooks['when_user_gets_comment'],
		'label' => __( 'When user gets comment', 'myloyal' ),
		'fields' => [
			[
				'type' => 'string',
				'string' => __( 'for his post, if the post is', 'myloyal' )
			],
			[
				'name' => 'sel_post',
				'type' => 'select',
				'label' => 'Selected Post',
				'desc' => '',
				'options' => [
					'all' => __( 'Any post', 'myloyal' ),
					'sel_post' => __( 'Any of the following post', 'myloyal')
				],
				'default' => 'all'
			],
			[
				'name' => 'sel_post_ids',
				'type' => 'select',
				'multiple' => true,
				'options' => [],
				'visible' => [
					[
						'key' => 'sel_post',
						'val' => 'sel_post'
					]
				],
			],
			[
				'type' => 'string',
				'string' => 'and the post is of '
			],
			[
				'name' => 'post_type',
				'type' => 'select',
				'label' => 'Post Type',
				'desc' => '',
				'multiple' => true,
				'options' => array_merge( ['all' => 'Any'], get_post_types()),
				'default' => 'all'
			],
			[
				'type' => 'string',
				'string' => 'post type, and the post status is '
			],
			[
				'name' => 'post_status',
				'type' => 'select',
				'label' => 'Post Status',
				'desc' => '',
				'multiple' => true,
				'options' => array_merge( ['all' => 'Any'],get_post_statuses()),
				'default' => 'publish'
			],
			[
				'type' => 'string',
				'string' => ' and the post creator '
			],
			[
				'name' => 'post_author_constrain',
				'type' => 'select',
				'options' => [
					'include' => __( 'is', 'myloyal' ),
					'exclude' => __( 'is not', 'myloyal' ),
				]
			],
			[
				'name' => 'constrain_author_list',
				'type' => 'select',
				'options' => [
					'all' => __( 'any', 'myloyal' ),
					'sel_user' => __( 'following', 'myloyal' ),
				]
			],
			[
				'type' => 'string',
				'string' => ' user(s) '
			],
			[
				'name' => 'constrain_author_ids',
				'type' => 'select',
				'options' => [],
				'multiple' => true,
				'visible' => [
					[
						'key' => 'constrain_author_list',
						'val' => 'sel_user'
					]
				],
			],
			[
				'type' => 'string',
				'string' => ' and have '
			],
			[
				'name' => 'constrain_author_role',
				'type' => 'select',
				'multiple' => true,
				'options' => array_merge( ['all' => __( 'any', 'myloyal' )], $roles )
			],
			[
				'type' => 'string',
				'string' => ' role(s) '
			],
			[
				'type' => 'string',
				'string' => ' and if the commentator '
			],
			[
				'name' => 'commenter_constrain',
				'type' => 'select',
				'options' => [
					'include' => __( 'is', 'myloyal' ),
					'exclude' => __( 'is not', 'myloyal' ),
				]
			],
			[
				'name' => 'constrain_commenter_list',
				'type' => 'select',
				'options' => [
					'all' => __( 'any', 'myloyal' ),
					'sel_user' => __( 'following', 'myloyal' ),
				]
			],
			[
				'type' => 'string',
				'string' => ' user(s) '
			],
			[
				'name' => 'constrain_commenter_ids',
				'type' => 'select',
				'options' => [],
				'visible' => [
					[
						'key' => 'constrain_commenter_list',
						'val' => 'sel_user'
					]
				],
			],
			[
				'type' => 'string',
				'string' => ' and have '
			],
			[
				'name' => 'constrain_commenter_role',
				'type' => 'select',
				'multiple' => true,
				'options' => array_merge( ['all' => __( 'any', 'myloyal' )], $roles )
			],
			[
				'type' => 'string',
				'string' => ' role(s) '
			],
			[
				'type' => 'string',
				'string' => ' Then '
			],
			[
				'name' => 'action',
				'type' => 'select',
				'label' => 'Action',
				'options' => [
					'add' => 'Add',
					'deduct' => 'Deduct'
				],
				'default' => 'add',
				'desc' => '',
			],
			[
				'name' => 'point',
				'type' => 'number',
				'label' => 'Point',
				'default' => 1,
				'desc' => ''
			],
			[
				'type' => 'string',
				'string' => __( 'to', 'myloyal' )
			],
			[
				'name' => 'target_actor',
				'type' => 'select',
				'label' => '',
				'options' => [
					'post_author' => __( 'Post Author', 'myloyal'),
					/*'all' => __( 'All Users', 'myloyal' ),
					'roles' => __( 'Following Roles', 'myloyal' ),
					'users' => __( 'Specific Users', 'myloyal' )*/
				],
				'default' => 'post_author'
			],

		]
	],
	'when_user_login' => [
		'hook' => $myloyal_case_hooks['when_user_login'],
		'label' => 'When User Login',
		'fields' => [
			//
			[
				'type' => 'string',
				'string' => __( 'If the user ', 'myloyal' )
			],
			[
				'name' => 'user_inclusion_type',
				'type' => 'select',
				'label' => '',
				'options' => [
					'include' => __( 'is', 'myloyal' ),
					'exclude' => __( 'is not', 'myloyal' )
				],
				'default' => 'all'
			],
			[
				'name' => 'applicable_for-user_type',
				'type' => 'select',
				'label' => '',
				'options' => [
					'all' => __( 'any', 'myloyal' ),
					'users' => __( 'following', 'myloyal' )
				],
				'default' => 'all'
			],
			[
				'type' => 'string',
				'string' => ' user(s)'
			],
			[
				'name' => 'applicable_for-users',
				'type' => 'select',
				'label' => 'Selected Users',
				'multiple' => true,
				'desc' => 'Users to who this rule will be applied',
				'options' => [
					'user1' => 'User1'
				],
				'visible' => [
					[
						'key' => 'applicable_for-user_type',
						'val' => 'users'
					]
				],
				'default' => ''
			],
			[
				'type' => 'string',
				'string' => ' , and '
			],
			[
				'name' => 'role_inclusion_type',
				'type' => 'select',
				'label' => '',
				'options' => [
					'include' => __( 'has', 'myloyal' ),
					'exclude' => __( 'has not', 'myloyal' )
				],
				'default' => 'all'
			],
			[
				'name' => 'applicable_for-role_type',
				'type' => 'select',
				'label' => '',
				'options' => [
					'all' => __( 'any', 'myloyal' ),
					'roles' => __( 'following', 'myloyal' )
				],
				'default' => 'all'
			],
			[
				'type' => 'string',
				'string' => ' role(s)'
			],
			[
				'name' => 'applicable_for-roles',
				'type' => 'select',
				'label' => 'Selected Roles',
				'multiple' => true,
				'desc' => 'Roles with users to who this rule will be applied',
				'options' => [
					'role1' => 'Role1'
				],
				'visible' => [
					[
						'key' => 'applicable_for-role_type',
						'val' => 'roles'
					]
				],
				'default' => ''
			],
			[
				'type' => 'string',
				'string' => ' Then '
			],
			//
			[
				'name' => 'action',
				'type' => 'select',
				'label' => 'Action',
				'options' => [
					'add' => 'Add',
					'deduct' => 'Deduct'
				],
				'default' => 'add',
				'desc' => '',
			],
			[
				'name' => 'point',
				'type' => 'number',
				'label' => 'Point',
				'default' => 1,
				'desc' => ''
			],
			[
				'type' => 'string',
				'string' => __( 'to', 'myloyal' )
			],
			[
				'name' => 'target_actor',
				'type' => 'select',
				'label' => '',
				'options' => [
					'own' => __( 'him', 'myloyal'),
				],
				'default' => 'own'
			],
			//
		]
	],
	'when_user_creates_post' => [
		'hook' => $myloyal_case_hooks['when_user_creates_post'],
		'label' => __( 'When user creates/updates post', 'myloyal' ),
		'fields' => [
			[
				'type' => 'string',
				'string' => __( 'If the user is ', 'myloyal' )
			],
			[
				'name' => 'user_type',
				'type' => 'select',
				'options' => [
					'all' => __( 'any', 'myloyal' ),
					'sel_user' => __( 'following', 'myloyal' ),
				]
			],
			[
				'type' => 'string',
				'string' => __( ' user(s) ', 'myloyal' )
			],
			[
				'name' => 'sel_users',
				'type' => 'select',
				'multiple' => true,
				'options' => [
					'user1' => __( 'User1', 'myloyal' ),
					'user2' => __( 'User2', 'myloyal' )
				],
				'visible' => [
					[
						'key' => 'user_type',
						'val' => 'sel_user'
					]
				],
			],
			[
				'type' => 'string',
				'string' => __( ' ,and if the user has ', 'myloyal' )
			],
			[
				'name' => 'role_types',
				'type' => 'select',
				'multiple' => true,
				'options' => array_merge( ['all' => __( 'any', 'myloyal' )], $roles )
			],
			[
				'type' => 'string',
				'string' => __( ' roles, and if the post has ', 'myloyal' )
			],
			[
				'name' => 'post_statuses',
				'type' => 'select',
				'multiple' => true,
				'options' => array_merge( ['all' => __( 'any', 'myloyal' )], get_post_statuses() )
			],
			[
				'type' => 'string',
				'string' => __( ' post status and has ', 'myloyal' )
			],
			[
				'name' => 'post_types',
				'type' => 'select',
				'multiple' => true,
				'options' => array_merge( ['all' => __( 'any', 'myloyal' )], get_post_types() )
			],
			[
				'type' => 'string',
				'string' => __( ' post type(s), and if the post is ', 'myloyal' )
			],
			[
				'name' => 'is_update',
				'type' => 'select',
				'multiple' => true,
				'options' => [
					'false' => __( 'created', 'myloyal' ),
					'true' => __( 'updated', 'myloyal' ),
				]
			],
			[
				'type' => 'string',
				'string' => '<div></div>'
			],
			[
				'type' => 'string',
				'string' => 'Then '
			],
			[
				'name' => 'action',
				'type' => 'select',
				'label' => 'Action',
				'options' => [
					'add' => 'Add',
					'deduct' => 'Deduct'
				],
				'default' => 'add',
				'desc' => '',
			],
			[
				'name' => 'point',
				'type' => 'number',
				'label' => 'Point',
				'default' => 1,
				'desc' => ''
			],
			[
				'type' => 'string',
				'string' => __( 'to', 'myloyal' )
			],
			[
				'name' => 'target_actor',
				'type' => 'select',
				'label' => '',
				'options' => [
					'own' => __( 'Who perform this', 'myloyal'),
				],
				'default' => 'own'
			]
		]
	],
        'when_user_creates_comment' => [
	        'hook' => $myloyal_case_hooks['when_user_creates_comment'],
	        'label' => __( 'When user creates comment', 'myloyal' ),
	        'fields' => [
		        [
			        'type' => 'string',
			        'string' => __( 'in a post, if the post is', 'myloyal' )
		        ],
		        [
			        'name' => 'sel_post',
			        'type' => 'select',
			        'label' => 'Selected Post',
			        'desc' => '',
			        'options' => [
				        'all' => __( 'Any post', 'myloyal' ),
				        'sel_post' => __( 'Any of the following post', 'myloyal')
			        ],
			        'default' => 'all'
		        ],
		        [
			        'name' => 'sel_post_ids',
			        'type' => 'select',
			        'multiple' => true,
			        'options' => [],
			        'visible' => [
				        [
					        'key' => 'sel_post',
					        'val' => 'sel_post'
				        ]
			        ],
		        ],
		        [
			        'type' => 'string',
			        'string' => 'and the post is of '
		        ],
		        [
			        'name' => 'post_type',
			        'type' => 'select',
			        'label' => 'Post Type',
			        'desc' => '',
			        'multiple' => true,
			        'options' => array_merge( ['all' => 'Any'], get_post_types()),
			        'default' => 'all'
		        ],
		        [
			        'type' => 'string',
			        'string' => 'post type, and the post status is '
		        ],
		        [
			        'name' => 'post_status',
			        'type' => 'select',
			        'label' => 'Post Status',
			        'desc' => '',
			        'multiple' => true,
			        'options' => array_merge( ['all' => 'Any'],get_post_statuses()),
			        'default' => 'publish'
		        ],
		        [
			        'type' => 'string',
			        'string' => ' and the post creator '
		        ],
		        [
			        'name' => 'post_author_constrain',
			        'type' => 'select',
			        'options' => [
				        'include' => __( 'is', 'myloyal' ),
				        'exclude' => __( 'is not', 'myloyal' ),
			        ]
		        ],
		        [
			        'name' => 'constrain_author_list',
			        'type' => 'select',
			        'options' => [
				        'all' => __( 'any', 'myloyal' ),
				        'sel_user' => __( 'following', 'myloyal' ),
			        ]
		        ],
		        [
			        'type' => 'string',
			        'string' => ' user(s) '
		        ],
		        [
			        'name' => 'constrain_author_ids',
			        'type' => 'select',
			        'options' => [],
			        'multiple' => true,
			        'visible' => [
				        [
					        'key' => 'constrain_author_list',
					        'val' => 'sel_user'
				        ]
			        ],
		        ],
		        [
			        'type' => 'string',
			        'string' => ' and have '
		        ],
		        [
			        'name' => 'constrain_author_role',
			        'type' => 'select',
			        'multiple' => true,
			        'options' => array_merge( ['all' => __( 'any', 'myloyal' )], $roles )
		        ],
		        [
			        'type' => 'string',
			        'string' => ' role(s) '
		        ],
		        [
			        'type' => 'string',
			        'string' => ' and if the commentator '
		        ],
		        [
			        'name' => 'commenter_constrain',
			        'type' => 'select',
			        'options' => [
				        'include' => __( 'is', 'myloyal' ),
				        'exclude' => __( 'is not', 'myloyal' ),
			        ]
		        ],
		        [
			        'name' => 'constrain_commenter_list',
			        'type' => 'select',
			        'options' => [
				        'all' => __( 'any', 'myloyal' ),
				        'sel_user' => __( 'following', 'myloyal' ),
			        ]
		        ],
		        [
			        'type' => 'string',
			        'string' => ' user(s) '
		        ],
		        [
			        'name' => 'constrain_commenter_ids',
			        'type' => 'select',
			        'options' => [],
			        'visible' => [
				        [
					        'key' => 'constrain_commenter_list',
					        'val' => 'sel_user'
				        ]
			        ],
		        ],
		        [
			        'type' => 'string',
			        'string' => ' and have '
		        ],
		        [
			        'name' => 'constrain_commenter_role',
			        'type' => 'select',
			        'multiple' => true,
			        'options' => array_merge( ['all' => __( 'any', 'myloyal' )], $roles )
		        ],
		        [
			        'type' => 'string',
			        'string' => ' role(s) '
		        ],
		        [
			        'type' => 'string',
			        'string' => ' Then '
		        ],
		        [
			        'name' => 'action',
			        'type' => 'select',
			        'label' => 'Action',
			        'options' => [
				        'add' => 'Add',
				        'deduct' => 'Deduct'
			        ],
			        'default' => 'add',
			        'desc' => '',
		        ],
		        [
			        'name' => 'point',
			        'type' => 'number',
			        'label' => 'Point',
			        'default' => 1,
			        'desc' => ''
		        ],
		        [
			        'type' => 'string',
			        'string' => __( 'to', 'myloyal' )
		        ],
		        [
			        'name' => 'target_actor',
			        'type' => 'select',
			        'label' => '',
			        'options' => [
				        'commenter' => __( 'Commenter', 'myloyal'),
				        /*'all' => __( 'All Users', 'myloyal' ),
						'roles' => __( 'Following Roles', 'myloyal' ),
						'users' => __( 'Specific Users', 'myloyal' )*/
			        ],
			        'default' => 'post_author'
		        ],

	        ]
        ],
        'when_user_updates_comment' => [
	        'hook' => $myloyal_case_hooks['when_user_updates_comment'],
	        'label' => __( 'When user updates comment', 'myloyal' ),
	        'fields' => [
		        [
			        'type' => 'string',
			        'string' => __( 'in a post, if the post is', 'myloyal' )
		        ],
		        [
			        'name' => 'sel_post',
			        'type' => 'select',
			        'label' => 'Selected Post',
			        'desc' => '',
			        'options' => [
				        'all' => __( 'Any post', 'myloyal' ),
				        'sel_post' => __( 'Any of the following post', 'myloyal')
			        ],
			        'default' => 'all'
		        ],
		        [
			        'name' => 'sel_post_ids',
			        'type' => 'select',
			        'multiple' => true,
			        'options' => [],
			        'visible' => [
				        [
					        'key' => 'sel_post',
					        'val' => 'sel_post'
				        ]
			        ],
		        ],
		        [
			        'type' => 'string',
			        'string' => 'and the post is of '
		        ],
		        [
			        'name' => 'post_type',
			        'type' => 'select',
			        'label' => 'Post Type',
			        'desc' => '',
			        'multiple' => true,
			        'options' => array_merge( ['all' => 'Any'], get_post_types()),
			        'default' => 'all'
		        ],
		        [
			        'type' => 'string',
			        'string' => 'post type, and the post status is '
		        ],
		        [
			        'name' => 'post_status',
			        'type' => 'select',
			        'label' => 'Post Status',
			        'desc' => '',
			        'multiple' => true,
			        'options' => array_merge( ['all' => 'Any'],get_post_statuses()),
			        'default' => 'publish'
		        ],
		        [
			        'type' => 'string',
			        'string' => ' and the post creator '
		        ],
		        [
			        'name' => 'post_author_constrain',
			        'type' => 'select',
			        'options' => [
				        'include' => __( 'is', 'myloyal' ),
				        'exclude' => __( 'is not', 'myloyal' ),
			        ]
		        ],
		        [
			        'name' => 'constrain_author_list',
			        'type' => 'select',
			        'options' => [
				        'all' => __( 'any', 'myloyal' ),
				        'sel_user' => __( 'following', 'myloyal' ),
			        ]
		        ],
		        [
			        'type' => 'string',
			        'string' => ' user(s) '
		        ],
		        [
			        'name' => 'constrain_author_ids',
			        'type' => 'select',
			        'options' => [],
			        'multiple' => true,
			        'visible' => [
				        [
					        'key' => 'constrain_author_list',
					        'val' => 'sel_user'
				        ]
			        ],
		        ],
		        [
			        'type' => 'string',
			        'string' => ' and have '
		        ],
		        [
			        'name' => 'constrain_author_role',
			        'type' => 'select',
			        'multiple' => true,
			        'options' => array_merge( ['all' => __( 'any', 'myloyal' )], $roles )
		        ],
		        [
			        'type' => 'string',
			        'string' => ' role(s) '
		        ],
		        [
			        'type' => 'string',
			        'string' => ' and if the commentator '
		        ],
		        [
			        'name' => 'commenter_constrain',
			        'type' => 'select',
			        'options' => [
				        'include' => __( 'is', 'myloyal' ),
				        'exclude' => __( 'is not', 'myloyal' ),
			        ]
		        ],
		        [
			        'name' => 'constrain_commenter_list',
			        'type' => 'select',
			        'options' => [
				        'all' => __( 'any', 'myloyal' ),
				        'sel_user' => __( 'following', 'myloyal' ),
			        ]
		        ],
		        [
			        'type' => 'string',
			        'string' => ' user(s) '
		        ],
		        [
			        'name' => 'constrain_commenter_ids',
			        'type' => 'select',
			        'options' => [],
			        'visible' => [
				        [
					        'key' => 'constrain_commenter_list',
					        'val' => 'sel_user'
				        ]
			        ],
		        ],
		        [
			        'type' => 'string',
			        'string' => ' and have '
		        ],
		        [
			        'name' => 'constrain_commenter_role',
			        'type' => 'select',
			        'multiple' => true,
			        'options' => array_merge( ['all' => __( 'any', 'myloyal' )], $roles )
		        ],
		        [
			        'type' => 'string',
			        'string' => ' role(s) '
		        ],
		        [
			        'type' => 'string',
			        'string' => ' Then '
		        ],
		        [
			        'name' => 'action',
			        'type' => 'select',
			        'label' => 'Action',
			        'options' => [
				        'add' => 'Add',
				        'deduct' => 'Deduct'
			        ],
			        'default' => 'add',
			        'desc' => '',
		        ],
		        [
			        'name' => 'point',
			        'type' => 'number',
			        'label' => 'Point',
			        'default' => 1,
			        'desc' => ''
		        ],
		        [
			        'type' => 'string',
			        'string' => __( 'to', 'myloyal' )
		        ],
		        [
			        'name' => 'target_actor',
			        'type' => 'select',
			        'label' => '',
			        'options' => [
				        'commenter' => __( 'Commenter', 'myloyal'),
				        /*'all' => __( 'All Users', 'myloyal' ),
						'roles' => __( 'Following Roles', 'myloyal' ),
						'users' => __( 'Specific Users', 'myloyal' )*/
			        ],
			        'default' => 'post_author'
		        ],

	        ]
        ],
        'when_user_create_user' => [
	        'hook' => $myloyal_case_hooks['when_user_create_user'],
	        'label' => 'When user create user',
	        'fields' => array_merge( $common_fields, [
		        [
			        'type' => 'string',
			        'string' => 'for creating user with'
		        ],
		        [
			        'name' => 'role',
			        'type' => 'select',
			        'multiple' => true,
			        'label' => '',
			        'desc' => '',
			        'options' => [
				        'all' => __( 'Any', 'myloyal' ),
				        'role1' => __( 'Role1', 'myloyal' )
			        ],
			        'default' => 'all'
		        ],
		        [
			        'type' => 'string',
			        'string' => ' role'
		        ]
	        ])
        ],
];

$myloyal_badge_rule = [
	'when_user_reach_point' => [
		//'hook' => 'save_post',
		//'label' => 'When user create post',
		'fields' => array_merge( [], [
			[
				'type' => 'string',
				'string' => 'When '
			],
			[
				'name' => 'actor',
				'type' => 'select',
				'label' => '',
				'desc' => '',
				'options' => [
					'all' => 'Any User',
					'users' => 'Following Users',
					'roles' => 'Following Roles'
				],
				'default' => 'all'
			],
			//
			[
				'name' => 'actor-users',
				'type' => 'select',
				//'label' => 'Selected Users',
				//'desc' => 'Users to who this rule will be applied',
				'options' => [
					'user1' => 'User1'
				],
				'visible' => [
					[
						'key' => 'actor',
						'val' => 'users'
					]
				],
				'default' => ''
			],
			[
				'name' => 'actor-roles',
				'type' => 'select',
				'label' => 'Selected Roles',
				'desc' => 'Roles with users to who this rule will be applied',
				'options' => [
					'role1' => 'Role1'
				],
				'visible' => [
					[
						'key' => 'actor',
						'val' => 'roles'
					]
				],
				'default' => ''
			],
			[
				'type' => 'string',
				'string' => ' reaches'
			],
			[
				'name' => 'target_point',
				'type' => 'number',
				'label' => 'Point',
				'default' => 0,
			],
			[
				'type' => 'string',
				'string' => 'They will get the badge'
			]
		])
	],
];

$data = [
	'when_user_login' => [
		[
			'action' => 'add',
			'point' => 1,
			'target_actor' => 'roles',
			'sel_roles' => ['administrator'],
			'exception_for' => 'no',
			'excluded_roles' => []
		]
	]
];