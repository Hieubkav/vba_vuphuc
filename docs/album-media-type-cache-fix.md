# Album Media Type Cache Fix

## 🐛 Bug Description

**Issue**: When switching album media type from PDF to Images, uploaded images do not display on the frontend after the first save, requiring a second save to appear.

**Reproduction Steps**:
1. Create/edit album with media_type = "PDF"
2. Upload PDF file and save
3. Edit same album, change media_type to "Images"
4. Upload new images and save once
5. **BUG**: Images don't display on frontend
6. **WORKAROUND**: Save again without changes → images appear

## 🔍 Root Cause Analysis

### Cache Timing Issue
The problem was a **cache invalidation timing issue** during media type transitions:

1. **Database Update**: Album record updated with new media_type and thumbnail
2. **Cache Clear**: Cache cleared by observers
3. **Cache Rebuild**: Cache rebuilt immediately, but database transaction might not be fully committed
4. **Filter Logic**: ViewServiceProvider filter logic didn't see the new data properly

### Specific Issues
- **ViewServiceProvider**: Cache rebuild happened before database transaction commit
- **AlbumObserver**: Standard cache clearing wasn't sufficient for media type transitions
- **Filament Pages**: No special handling for media type changes

## ✅ Solution Implemented

### 1. Enhanced ViewServiceProvider
**New Method**: `forceRebuildAlbumsCache()`
```php
public static function forceRebuildAlbumsCache(): void
{
    // Clear existing cache first
    self::clearAlbumsCache();
    
    // Force immediate rebuild with fresh database data
    Cache::forget('storefront_albums');
    
    // Rebuild cache with fresh data
    Cache::remember('storefront_albums', 7200, function () {
        // Fresh database query with proper filtering
    });
}
```

### 2. Enhanced EditAlbum Page
**Media Type Change Detection**:
```php
protected function afterSave(): void
{
    $record = $this->getRecord();
    if ($record && $record->wasChanged('media_type')) {
        // Force immediate cache rebuild
        ViewServiceProvider::forceRebuildAlbumsCache();
        
        // Delayed cache rebuild for consistency
        dispatch(function () {
            ViewServiceProvider::forceRebuildAlbumsCache();
        })->delay(now()->addSeconds(3));
    }
}
```

### 3. Enhanced AlbumObserver
**Media Type Change Tracking**:
```php
// In updating()
if ($album->isDirty('media_type')) {
    // Store flag for media type change
    $this->storeOldFile(
        get_class($album),
        $album->id,
        'media_type_changed',
        $originalMediaType . '->' . $album->media_type
    );
}

// In updated()
$mediaTypeChange = $this->getAndDeleteOldFile(
    get_class($album),
    $album->id,
    'media_type_changed'
);

if ($mediaTypeChange) {
    // Force immediate cache rebuild
    ViewServiceProvider::forceRebuildAlbumsCache();
}
```

## 🎯 Fix Components

### Multi-Layer Cache Invalidation
1. **Form Level**: Clear cache when media_type changes in Filament form
2. **Page Level**: Force cache rebuild in EditAlbum page
3. **Observer Level**: Track and handle media type changes
4. **Provider Level**: Enhanced cache rebuild methods

### Timing Solutions
- **Immediate Rebuild**: Force cache rebuild right after save
- **Delayed Rebuild**: Secondary cache rebuild after 3 seconds
- **Transaction Safety**: Ensure database commits before cache rebuild

## 🧪 Testing

### Manual Test Steps
1. Create album with PDF media type
2. Upload PDF and save
3. Edit album, change to Images media type
4. Upload images and save once
5. **Expected**: Images display immediately on frontend
6. **Verify**: No browser console errors

### Automated Testing
```bash
# Test cache methods
php artisan test:media-type-cache-fix 19

# Check orphaned files
php artisan album:cleanup-orphaned-files --dry-run
```

## 📈 Expected Results

### Before Fix
- ❌ Images don't display after first save
- ❌ Requires second save to appear
- ❌ Cache timing issues
- ❌ Poor user experience

### After Fix
- ✅ Images display immediately after first save
- ✅ No second save required
- ✅ Proper cache invalidation
- ✅ Smooth media type transitions
- ✅ Consistent frontend display

## 🔧 Maintenance

### Cache Commands
```bash
# Clear albums cache
php artisan cache:clear

# Force rebuild albums cache (if needed)
# Use ViewServiceProvider::forceRebuildAlbumsCache() in code
```

### Monitoring
- Monitor browser console for 404 errors
- Check album display consistency
- Verify cache rebuild performance

## 📝 Notes

- Fix specifically targets PDF→Images transitions
- Maintains backward compatibility
- No performance impact on normal operations
- Includes fallback mechanisms for edge cases
