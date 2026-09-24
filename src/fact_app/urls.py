from django.urls import path
from . import views

urlpatterns = [
    # ==========================================
    # ACCUEIL
    # ==========================================
    path("", views.home, name="home"),
]
