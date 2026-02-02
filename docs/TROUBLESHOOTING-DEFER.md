# Troubleshooting JavaScript Defer Issues

## How to Verify Defer is Working

### Method 1: Check HTML Source

1. View page source (Right-click → View Page Source)
2. Search for `main.js`
3. You should see: `<script src="..." defer></script>`

If you see `defer` attribute, it's working. If not, WordPress might not be outputting it correctly.

### Method 2: Browser DevTools

1. Open DevTools (F12)
2. Go to **Network** tab
3. Reload page
4. Find `main.js` in the list
5. Check the **Initiator** column - it should show the script is deferred

### Method 3: Check Script Execution Timing

Add this temporary code to verify timing:

```javascript
// Add to main.js at the very top
console.log('Script loaded at:', document.readyState);
console.log(
  'Defer working:',
  document.readyState === 'loading' || document.readyState === 'interactive'
);
```

If defer is working:

- Script executes when `document.readyState` is `interactive` or `complete`
- Not when it's `loading`

## The Error You're Seeing

**Error:** "A listener indicated an asynchronous response by returning true, but the message channel closed before a response was received"

**This error is typically caused by:**

1. **Browser Extensions** - Extensions trying to communicate with the page
2. **Service Worker Communication** - Messages sent but channel closes
3. **WordPress Plugins** - Plugins using Service Workers or messaging

**It's usually NOT caused by defer**, but defer can affect timing.

## Testing Without Defer

If you want to test if defer is causing the issue:

### Option 1: Temporarily Remove Defer

In `src/functions/enqueue.php`, comment out this line:

```php
// wp_script_add_data( 'tsm-theme-script', 'defer', true );
```

Then rebuild and test.

### Option 2: Use Conditional Defer

Only defer on certain pages:

```php
// Only defer on non-gallery pages
if ( ! is_post_type_archive( 'gallery' ) && ! is_tax( 'gallery_category' ) ) {
    wp_script_add_data( 'tsm-theme-script', 'defer', true );
}
```

## Service Worker Fixes Applied

I've improved the Service Worker registration to:

1. ✅ Check if page is unloading before registering
2. ✅ Check if service worker already controls the page
3. ✅ Better error handling to ignore expected errors
4. ✅ Proper scope setting
5. ✅ Removed update interval that might cause message channel issues

## Common Causes of Freezing

1. **JavaScript Errors** - Check console for other errors
2. **Infinite Loops** - Scripts running forever
3. **Blocking Operations** - Synchronous code blocking the thread
4. **Service Worker Issues** - Registration conflicts
5. **Plugin Conflicts** - Other plugins interfering

## Debugging Steps

1. **Check Browser Console**
   - Look for JavaScript errors
   - Check if scripts are loading
   - Verify defer attribute is present

2. **Check Network Tab**
   - Verify `main.js` loads successfully
   - Check load timing
   - Look for failed requests

3. **Test in Incognito Mode**
   - Rules out browser extensions
   - Clean cache/test

4. **Disable Other Plugins**
   - Test if plugins are causing conflicts
   - Re-enable one by one

5. **Test Without Defer**
   - Temporarily remove defer
   - See if issue persists
   - If it fixes it, defer might be causing timing issues

## Expected Behavior with Defer

**With Defer:**

- Script downloads in parallel (non-blocking)
- Script executes after HTML parsing
- Script executes before `DOMContentLoaded`
- Page renders faster

**If Defer Causes Issues:**

- Script might execute too late
- Dependencies might not be ready
- Service Worker timing might be off

## Solution Applied

I've updated the Service Worker registration to be more robust:

- ✅ Checks page state before registering
- ✅ Handles errors gracefully
- ✅ Avoids message channel conflicts
- ✅ Proper scope and error handling

This should fix the "message channel closed" error you're seeing.

## Next Steps

1. **Test the galleries page** - The improved Service Worker code should fix the error
2. **Verify defer is working** - Use Method 1 above to check HTML source
3. **If issue persists** - Temporarily remove defer to test
4. **Check for other errors** - The freeze might be from a different issue
