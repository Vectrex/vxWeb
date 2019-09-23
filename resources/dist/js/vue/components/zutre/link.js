
/**
 * ZLink
 *
 * @author Maciej Lisowski <maciej.lisowski.elk@gmail.com>
 * @prop {String} href
 * @prop {Object} link
 * @prop {String} name
 * @prop {String} icon
 * @prop {String} activeClass
 * @prop {Boolean} active
 * @prop {Boolean} exact
 */
export default {
		template: '<a v-if="!hasLink" :href="linkHref" :class="linkClass" v-bind:data-badge="badge" @click="$emit(&#39;click&#39;)"><template v-if="hasDefaultSlot"><z-icon v-if="hasIcon" :name="icon"> <slot></slot></z-icon></template><template v-else-if="!hasDefaultSlot"><z-icon v-if="hasIcon" :name="icon"> {{ linkName }}</z-icon></template></a><router-link v-else-if="!hasHref &amp;&amp; hasLink" :to="linkRouter" :class="linkClass" @click="$emit(&#39;click&#39;)" v-bind:active-class="activeClass" v-bind:exact="exact" v-bind:data-badge="badge"><template v-if="hasDefaultSlot"><z-icon v-if="hasIcon" :name="icon"> <slot></slot></z-icon></template><template v-else-if="!hasDefaultSlot"><z-icon v-if="hasIcon" :name="icon"> {{ linkName }}</z-icon></template></router-link>',
  name: 'Link',
  props: {
    href: {
      type: String
    },
    link: {
      type: Object
    },
    name: String,
    icon: String,
    activeClass: {
      type: String,
      default: () => 'active'
    },
    active: {
      type: Boolean,
      default: () => false
    },
    exact: {
      type: Boolean,
      default: () => false
    },
    badge: String
  },
  computed: {
    linkClass: function() {
      let css = { 'menu-item': true, badge: false };

      if (this.active === true && typeof this.activeClass === 'string') {
        css[this.activeClass] = true
      }

      if (typeof this.badge !== 'undefined') {
        css.badge = true;
      }

      return css
    },
    hasIcon() {
      return (typeof this.icon !== 'undefined') ? true : false;
    },
    hasHref() {
      return (typeof this.href !== 'undefined') ? true : false;
    },
    hasLink() {
      return (typeof this.link !== 'undefined') ? true : false;
    },
    hasDefaultSlot() {
      return !!this.$slots.default;
    },
    linkName() {
      return this.name;
    },
    linkHref() {
      return this.href;
    },
    linkRouter() {
      return this.link;
    }
  }
}
