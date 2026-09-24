from django.shortcuts import render


# =========================================================
# ACCUEIL PUBLIC
# =========================================================
def home(request):
    return render(request, "base.html")