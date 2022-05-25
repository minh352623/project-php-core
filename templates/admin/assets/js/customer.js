function toSlug(title) {
  let slug = title.toLowerCase(); //chuyển thành chữ thường
  slug = slug.trim();
  slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, "a");
  slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, "e");
  slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, "i");
  slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, "o");
  slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, "u");
  slug = slug.replace(/ý|ỳ|ỷ|ỹ/gi, "y");
  slug = slug.replace(/đ/gi, "d");
  slug = slug.replace(/ /gi, "-");

  slug = slug.replace(
    /`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|/gi,
    ""
  );

  return slug;
}
const slug = document.querySelector(".slug");
const slugAuto = document.querySelector(".auto-slug");
let renderLink = document.querySelector(".render-link");
console.log(renderLink);
if (renderLink) {
  //lây slug
  let slug = "";
  if (slugAuto) {
    slug = "/" + slugAuto.value.trim();
  }
  renderLink.querySelector("span").innerHTML = `<a href="${
    rootUrl + slug
  }" target="_blank">${rootUrl + slug}</a>`;
}

if (slug !== null && slugAuto !== null) {
  slug.addEventListener("keyup", (e) => {
    if (!sessionStorage.getItem("save_slug")) {
      let title = e.target.value;

      if (title !== null) {
        let slug = toSlug(title);

        slugAuto.value = slug;
      }
    }
  });

  slug.addEventListener("change", () => {
    sessionStorage.setItem("save_slug", 1);
    let currentLink =
      rootUrl + "/" + prefixURl + "/" + slugAuto.value + ".html";
    renderLink.querySelector("span a").innerHTML = currentLink;
    renderLink.querySelector("span a").href = currentLink;
  });
  slugAuto.addEventListener("change", (e) => {
    let slugValue = e.target.value;
    if (slugValue.trim() == "") {
      sessionStorage.removeItem("save_slug");
      let slugNew = toSlug(slug.value);
      e.target.value = slugNew;
    }
    let currentLink =
      rootUrl + "/" + prefixURl + "/" + slugAuto.value + ".html";
    renderLink.querySelector("span a").innerHTML = currentLink;
    renderLink.querySelector("span a").href = currentLink;
  });
  if (slugAuto.value.trim() == "") {
    sessionStorage.removeItem("save_slug");
  }
}

//xử lý check editor

let classTextArea = document.querySelectorAll(".editor");
console.log(classTextArea);
if (classTextArea) {
  classTextArea.forEach((item, index) => {
    item.id = "editor_" + (index + 1);
    CKEDITOR.replace(item.id);
  });
}

//xử lý popup  ckfinder
function openCkFinder() {
  let chooseImages = document.querySelectorAll(".choose-image");
  if (chooseImages) {
    chooseImages.forEach((chooseImage) => {
      chooseImage.addEventListener("click", function (e) {
        e.preventDefault();
        let parentElementObject = this.parentElement;
        while (parentElementObject) {
          parentElementObject = parentElementObject.parentElement;
          if (parentElementObject.classList.contains("ckfinder-group")) {
            break;
          }
        }
        //Code mở popup Ckfinder
        CKFinder.popup({
          chooseFiles: true,
          width: 800,
          height: 600,
          onInit: function (finder) {
            finder.on("files:choose", function (evt) {
              let fileUrl = evt.data.files.first().getUrl();
              //Xử lý chèn link ảnh vào input
              parentElementObject.querySelector(".image-reder").value = fileUrl;
            });
            finder.on("file:choose:resizedImage", function (evt) {
              let fileUrl = evt.data.resizedUrl;
              //Xử lý chèn link ảnh vào input
            });
          },
        });
      });
    });
  }
}
openCkFinder();
//xử lý thêm thư viện dưới dạng Repeater
const galleryItemHtml = `
        <div class="gallery-item">
          <div class="row">
              <div class="col-11">
                  <div class="row ckfinder-group">
                      <div class="col-10">

                          <input type="text" class="form-control image-reder" placeholder="Đường dẫn ảnh..." name="gallery[]">
                      </div>
                      <div class="col-2">
                          <button type="button" class="btn btn-success  btn-block choose-image">Chọn ảnh</button>
                      </div>
                  </div>
              </div>
              <div class="col-1">
                  <a href="#" class="btn  btn-danger btn-block remove"><i class="fa fa-times"></i></a>
              </div>
          </div>

        </div>
`;
const addGalleryObject = document.querySelector(".add-gallery");
const galleryImageObject = document.querySelector(".gallery-images");

if (addGalleryObject) {
  addGalleryObject.addEventListener("click", function (e) {
    e.preventDefault();
    //phải chuyển chuổi html ở trên thành node thì khi add img nó mới k reset lại value rỗng
    //cách chuyển
    let galleryItemHtmlNode = new DOMParser()
      .parseFromString(galleryItemHtml, "text/html")
      .querySelector(".gallery-item");
    galleryImageObject.appendChild(galleryItemHtmlNode);
    openCkFinder();
  });
  galleryImageObject.addEventListener("click", function (e) {
    e.preventDefault();
    if (
      e.target.classList.contains("remove") ||
      e.target.parentElement.classList.contains("remove")
    ) {
      e.preventDefault();
      if (confirm("Bạn có chắc chắn muốn xóa!")) {
        let galleryItem = e.target;
        while (galleryItem) {
          galleryItem = galleryItem.parentElement;
          if (galleryItem.classList.contains("gallery-item")) {
            break;
          }
        }
        console.log(galleryItem);
        if (galleryItem) {
          galleryItem.remove();
        }
      }
    }
  });
}

const slideItem = `
<div class="slide-item">
<div class="row">
    <div class="col-11">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="">Tiêu đề</label>
                    <input type="text" placeholder="Tiêu đề.." name="home_slide[slide_title][]" class="form-control">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">Nút xem thêm</label>
                    <input name="home_slide[slide_button_text][]" placeholder="Chữ của nút..." class="form-control">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">Link xem thêm</label>
                    <input name="home_slide[slide_button_link][]" placeholder="Link của nút..." class="form-control">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">Link youtube</label>
                    <input name="home_slide[slide_youtube][]" placeholder="Link của nút..." class="form-control">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="">Ảnh 1</label>
                    <div class="row ckfinder-group">
                        <div class="col-10">

                            <input type="text" class="form-control image-reder"  placeholder="Đường dẫn ảnh..." name="home_slide[slide_image_1][]">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-success  btn-block choose-image"><i class="fas fa-upload"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">Ảnh 2</label>
                    <div class="row ckfinder-group">
                        <div class="col-10">

                            <input type="text" class="form-control image-reder"  placeholder="Đường dẫn ảnh..." name="home_slide[slide_image_2][]">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-success   btn-block choose-image"><i class="fas fa-upload"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">Ảnh nền</label>
                    <div class="row ckfinder-group">
                        <div class="col-10">

                            <input type="text" class="form-control image-reder"  placeholder="Đường dẫn ảnh..." name="home_slide[slide_bg][]">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-success   btn-block choose-image"><i class="fas fa-upload"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">Mô tả</label>
                    <textarea name="home_slide[slide_desc][]" placeholder="Mô tả slide..." class="form-control"></textarea>
                </div>
            </div>
            <div class="col-6">
            <div class="form-group">
                <label for="">Vị trí</label>
                <select name="home_slide[slide_position][]" class="form-control">
                    <option value="left">Trái</option>
                    <option value="center">Giữa</option>
                    <option value="right">Phải</option>

                </select>
            </div>
          </div>
        </div>
    </div>
    <div class="col-1">
        <a href="#" style="font-size:20px;" class="btn remove btn-block btn-danger">&times;</a>
    </div>
</div>
</div>
`;
const addSlideObject = document.querySelector(".add-slide");
const slideWarpperObject = document.querySelector(".slide-warpper");
if (addSlideObject != null && slideWarpperObject != null) {
  addSlideObject.addEventListener("click", function () {
    let slideItemHtmlNode = new DOMParser()
      .parseFromString(slideItem, "text/html")
      .querySelector(".slide-item");
    slideWarpperObject.appendChild(slideItemHtmlNode);
    openCkFinder();
  });
  slideWarpperObject.addEventListener("click", function (e) {
    e.preventDefault();
    if (
      e.target.classList.contains("remove") ||
      e.target.parentElement.classList.contains("remove")
    ) {
      e.preventDefault();
      if (confirm("Bạn có chắc chắn muốn xóa!")) {
        let slideItem = e.target;
        while (slideItem) {
          slideItem = slideItem.parentElement;
          if (slideItem.classList.contains("slide-item")) {
            break;
          }
        }
        console.log(slideItem);
        if (slideItem) {
          slideItem.remove();
        }
      }
    }
  });
}
// skill

const skillItem = `
<div class="skill-item">
<div class="row">
    <div class="col-11">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="">Tên năng lực</label>
                    <input type="text" name="home_about[skill][name][]" placeholder="Tên năng lực..." class="form-control">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">Giá trị</label>
                    <input type="text" min="0" max="100" name="home_about[skill][value][]" class="ranger form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="col-1">
        <a href="#" class="btn btn-danger btn-block remove">&times;</a>
    </div>
</div>

</div>
`;
const addSkillObject = document.querySelector(".add-skill");
const skillWarpperObject = document.querySelector(".skill-wrapper");
if (addSkillObject != null && skillWarpperObject != null) {
  addSkillObject.addEventListener("click", function () {
    let skillItemHtmlNode = new DOMParser()
      .parseFromString(skillItem, "text/html")
      .querySelector(".skill-item");
    skillWarpperObject.appendChild(skillItemHtmlNode);
    $(".ranger").ionRangeSlider({
      min: 0,
      max: 100,
      type: "single",
      step: 1,
      postfix: " %",
      prettify: false,
      hasGrid: true,
    });
  });
  skillWarpperObject.addEventListener("click", function (e) {
    e.preventDefault();
    if (
      e.target.classList.contains("remove") ||
      e.target.parentElement.classList.contains("remove")
    ) {
      e.preventDefault();
      if (confirm("Bạn có chắc chắn muốn xóa!")) {
        let skillItem = e.target;
        while (skillItem) {
          skillItem = skillItem.parentElement;
          if (skillItem.classList.contains("skill-item")) {
            break;
          }
        }
        console.log(skillItem);
        if (skillItem) {
          skillItem.remove();
        }
      }
    }
  });
}

//
$(".ranger").ionRangeSlider({
  min: 0,
  max: 100,
  type: "single",
  step: 1,
  postfix: " %",
  prettify: false,
  hasGrid: true,
});
