<div class="video-component" x-data="{ 
    videoUrl: @entangle('content.videoUrl'),
    editing: false,
    isYouTube: false,
    videoId: '',
    updateVideoUrl(url) {
        // استخراج معرف الفيديو من رابط يوتيوب
        const youtubeRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^\"&?\/\s]{11})/;
        const match = url.match(youtubeRegex);
        
        if (match && match[1]) {
            this.isYouTube = true;
            this.videoId = match[1];
            this.videoUrl = url;
            $wire.emit('componentSettingsUpdated', '{{ $componentId }}', {
                content: { videoUrl: url, videoId: match[1], type: 'youtube' }
            });
        } else {
            this.isYouTube = false;
            this.videoUrl = url;
            $wire.emit('componentSettingsUpdated', '{{ $componentId }}', {
                content: { videoUrl: url, type: 'custom' }
            });
        }
    }
}">
    <!-- وضع العرض -->
    <div x-show="!editing" 
         class="relative aspect-video rounded-lg overflow-hidden"
         x-on:dblclick="editing = true">
        
        <!-- يوتيوب -->
        <template x-if="isYouTube">
            <iframe 
                x-bind:src="'https://www.youtube.com/embed/' + videoId"
                class="absolute inset-0 w-full h-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </template>

        <!-- فيديو عادي -->
        <template x-if="!isYouTube && videoUrl">
            <video 
                x-bind:src="videoUrl"
                class="w-full h-full"
                controls>
                متصفحك لا يدعم تشغيل الفيديو.
            </video>
        </template>

        <!-- رسالة البداية -->
        <template x-if="!videoUrl">
            <div class="absolute inset-0 flex items-center justify-center bg-gray-100">
                <p class="text-gray-500">انقر مرتين لإضافة فيديو</p>
            </div>
        </template>
    </div>

    <!-- وضع التحرير -->
    <div x-show="editing" class="space-y-4 p-4 border rounded-lg">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">رابط الفيديو</label>
            <input 
                type="text"
                x-model="videoUrl"
                class="w-full p-2 border rounded"
                placeholder="أدخل رابط يوتيوب أو رابط فيديو مباشر">
        </div>

        <div class="flex justify-end gap-2">
            <button 
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                x-on:click="
                    updateVideoUrl(videoUrl);
                    editing = false;
                ">
                حفظ
            </button>
            <button 
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                x-on:click="editing = false">
                إلغاء
            </button>
        </div>
    </div>
</div>
