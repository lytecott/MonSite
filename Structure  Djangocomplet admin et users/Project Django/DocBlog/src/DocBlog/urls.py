
from django.contrib import admin
from django.urls import path, include

from django.conf import settings
from django.conf.urls.static import static


urlpatterns = [

    # ==========================================
    # ADMINISTRATION DJANGO
    # ==========================================

    path(
        "admin/",
        admin.site.urls
    ),

    # ==========================================
    # APPLICATION ACCOUNTS
    # ==========================================

    path(
        "",
        include("accounts.urls")
    ),
]


# ==========================================
# FICHIERS MEDIA
# ==========================================

if settings.DEBUG:

    urlpatterns += static(
        settings.MEDIA_URL,
        document_root=settings.MEDIA_ROOT
    )