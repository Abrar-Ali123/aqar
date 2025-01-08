<div>
    <h1>إدارة الخصائص</h1>

    <!-- حقل البحث عن الأيقونات -->
    <input type="text" id="icon-search" placeholder="ابحث عن أيقونة..." oninput="resetSearchAndSearchIcons()">

    <!-- قائمة الأيقونات -->
    <div id="icon-list" style="display: flex; flex-wrap: wrap; gap: 10px; max-height: 500px; overflow-y: auto;">
        <!-- سيتم تعبئة الأيقونات هنا باستخدام JavaScript -->
    </div>

    <!-- رسالة عند عدم العثور على نتائج -->
    <div id="no-results" style="color: red; display: none;">لم يتم العثور على نتائج للأيقونات.</div>

    <!-- عرض الأيقونة المختارة -->
    <div style="margin-top: 20px;">
        <h3>الأيقونة المختارة:</h3>
        <span id="selected-icon-display">
            @if ($selectedIcon)
                <i class="iconify" data-icon="{{ $selectedIcon }}"></i> <span>{{ $selectedIcon }}</span>
            @else
                <p>لم يتم اختيار أيقونة بعد.</p>
            @endif
        </span>
    </div>

    <!-- تضمين مكتبة Iconify -->
    <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>

    <script>
        // متغيرات للتحكم بالبحث
        let searchTerm = '';
        let allIcons = []; // قائمة تجمع الأيقونات من كل المكتبات

        // دالة لإعادة تعيين البحث
        function resetSearchAndSearchIcons() {
            searchTerm = document.getElementById("icon-search").value.toLowerCase();
            searchIcons();
        }

        // دالة البحث عن الأيقونات عبر Iconify
        async function searchIcons() {
            const iconList = document.getElementById("icon-list");
            iconList.innerHTML = '';
            document.getElementById("no-results").style.display = 'none'; // إخفاء رسالة "عدم العثور"

            // إذا كان مصطلح البحث فارغًا، لا نقوم بعمل استعلام
            if (!searchTerm) return;

            // جلب الأيقونات من Iconify
            await fetchIconifyIcons();

            // عرض الأيقونات المحملة
            displayIcons();
        }

        // دالة لعرض الأيقونات المحملة
        function displayIcons() {
            const iconList = document.getElementById("icon-list");
            iconList.innerHTML = ''; // إعادة تعيين القائمة

            if (allIcons.length === 0) {
                // إذا لم يتم العثور على أيقونات، إظهار رسالة
                document.getElementById("no-results").style.display = 'block';
                return;
            }

            // إخفاء رسالة "لم يتم العثور على نتائج"
            document.getElementById("no-results").style.display = 'none';

            allIcons.forEach(icon => {
                const iconElement = document.createElement("div");
                iconElement.style.cursor = "pointer";
                iconElement.style.padding = "10px";
                iconElement.style.fontSize = "24px";
                iconElement.innerHTML = icon.html;
                iconElement.onclick = () => selectIcon(icon.name);
                iconList.appendChild(iconElement);
            });

            // التحقق من تحميل Iconify وتحديث الأيقونات
            if (window.Iconify) {
                Iconify.scan(iconList);
            } else {
                console.error("Iconify لم يتم تحميل مكتبة");
            }
        }

        // جلب الأيقونات من Iconify لكل المكتبات
        async function fetchIconifyIcons() {
            const collections = [
                "fa", "mdi", "bi", "fe", "ion", "jam", "ant-design", "heroicons", "octicon", "ri",
                "bx", "simple-icons", "typcn", "wi", "zondicons", "gridicons", "vaadin", "css-gg",
                "tabler", "ph"
            ];

            allIcons = []; // إعادة تعيين قائمة الأيقونات

            // جلب الأيقونات من كل مكتبة
            for (const collection of collections) {
                try {
                    const response = await fetch(
                        `https://api.iconify.design/search?query=${encodeURIComponent(searchTerm)}&collection=${collection}&limit=10`
                    );

                    if (!response.ok) {
                        console.error(`Failed to fetch from ${collection}. Status: ${response.status}`);
                        continue;
                    }

                    const data = await response.json();
                    console.log(`Results from ${collection}:`, data); // عرض نتائج كل مكتبة في الـ Console

                    if (data.icons) {


                        data.icons.forEach(icon => {
                            const iconName = `${collection}:${icon}`;
                            const iconPureName = icon.split(':')[1];
                            allIcons.push({
                                name: iconName,
                                html: `<span class="icon--${collection} icon--${collection}--${iconPureName}"></span>`
                            });
                        });

                        addIconifyStylesheet(collection, data.icons);

                    } else {
                        console.log(`No icons found in ${collection} for "${searchTerm}"`);
                    }
                } catch (error) {
                    console.error(`Error fetching icons from ${collection}:`, error);
                    alert(`Error fetching icons from ${collection}: ${error.message}`);
                }
            }

            if (allIcons.length === 0) {
                console.log("لم يتم العثور على أيقونات في أي من المكتبات.");
            } else {
                console.log(`تم العثور على ${allIcons.length} أيقونة.`);
            }
        }

        function addIconifyStylesheet(lib, icons) {
            const extractedIcons = icons.map(icon => icon.split(':')[1]);

            if (extractedIcons.length == 0) {
                return;
            }

            // Join the extracted parts with a comma
            const iconString = extractedIcons.join(',');


            // Create a new link element
            const link = document.createElement('link');

            // Set the attributes for the link tag
            link.rel = 'stylesheet';
            link.href = 'https://api.iconify.design/' + lib + '.css?icons=' + iconString;

            // Append the link tag to the head of the document
            document.head.appendChild(link);

            console.log('Iconify stylesheet added successfully!');
        }

        // دالة لاختيار الأيقونة
        function selectIcon(icon) {
            @this.call('selectIcon', icon); // الاتصال بـ Livewire لتحديث الأيقونة المختارة
            document.getElementById("selected-icon-display").innerHTML =
                `<span class="iconify" data-icon="${icon}" data-inline="false"></span> ${icon}`;
        }
    </script>
</div>
