=== Speed-bumps ===
Contributors: fusioneng
Tags: content, advertisinq
Requires at least: 3.0.1
Tested up to: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Intelligently insert "speed bumps" into a piece of content.

== Description ==

Speed Bumps is a plugin which, given a piece of content such as a post, tries to intelligently determine the best spots to insert speed bumps, which can be
used for any type of content.

Theme authors can register any number of "speed bumps", which can be anything from graphical elements to advertising to recirculation navigation links. Each
speed bump can inherit from a default set of rules or implement its own as to where it can be inserted. All that is required is that it implement a function
which returns a string to be inserted into the content.


== Installation ==

It's a plugin! Install it like any other.

Onec you've installed the plugin, you'll have to register one or more speed bumps in order for it to have any effect. You'll also have to specifically call it to filter your content - the plugin doesn't attach any filters to `the_content` or other hooks by itself.

The simplest way to have Speed Bumps process all of your content and insert speed bumps into content everywhere is to simply add this filter:

```
Speed_Bumps()->register_speed_bump( 'speed_bump_sample', array(
	'string_to_inject' => function() { return '<div id="speed-bump-sample"></div>'; },
	'minimum_content_length' => false,
	'from_start' => false,
	'from_end' => false,
));

add_filter( 'the_content', function( $content ) {
	return apply_filters( 'speed_bumps_inject_content', $content );
}, 1 );

```

You can also selectively insert speed bumps into any string of content by calling Speed Bumps directly:

```
echo Speed_Bumps()->speed_bumps_inject_content( $content_to_be_inserted_into );
```

== Frequently Asked Questions ==

= What are the default rules? =

The default options for speed bumps are currently:

- never insert in a post fewer than 1200 character long.
- Can be anywhere, including before the first paragraph.
- Can not be inserted before or after an image, embed, or iframe.
- Must be at least one paragraph from other insertions.

= How to add more specific rules? =

Adding a custom rule for a speed bump is a matter of defining a function and hooking it to the `speed_bumps_{id}_constraints` filter. The function hooked to that filter will receive several arguments to determine the state of the content, surrounding paragraphs and other context, and can return `false` to block insertion.

Simple, stupid rule: You have a speed bump called "rickroll" which inserts a beautiful musical video throughout your content. 

```
register_speed_bump( 'rickroll', array(
	'string_to_inject' => function() { return '<iframe width="420" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>'; },
	'minimum_content_length' => false,
	'from_start' => false,
	'from_end' => false,
));
add_filter( 'the_content', 'insert_speed_bumps' );
```

But, maybe that's a little too extreme. You want to show it in certain situations, say, only when the previous paragraph contains the phrase 'give {something} up'. Here's how you would achieve that:

```
add_filter( 'speed_bumps_rickroll_constraints', 'give_you_up', 10, 4 );

function give_you_up( $can_insert, $context, $args, $already_inserted ) {
	if ( ! preg_match( '/give [^ ]+ up/i', $context['prev_paragraph'] ) ) {
		$can_insert = false;
	}
	return $can_insert;
}
```

You can also disable it altogether with this filter:

```
add_filter( 'speed_bumps_rickroll_constraints', '__return_false' );
```

= How do I remove rules =

Each rule is hooked to your speed bump's "constraints" filter. To remove a rule, simply remove the filter which defines that rule, like these lines for removing the default rules:

```
remove_filter( 'speed_bumps_myspeedbump_constraints', '\Speed_Bumps\Constraints\Text\Minimum_Text::content_is_long_enough_to_insert' );
```
