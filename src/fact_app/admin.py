from django.contrib import admin
from .models import *


class AdminCustomer(admin.ModelAdmin):
    list_display = ('name', 'email', 'phone', 'adresse', 'sex', 'age', 'city', 'zip_code')

class AdminInvoice(admin.ModelAdmin):   
    list_display = ('Customer','save_by','Invoice_date_time','total','last_updated_date', 'paid', 'Invoice_type') 

admin.site.register(Customer, AdminCustomer)
admin.site.register(Invoice, AdminInvoice)
admin.site.register(Article)