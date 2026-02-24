import React, { useEffect, useState } from 'react';
import { useI18n } from '../context/I18nContext';
import axios from 'axios';
import { Card, CardContent } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Textarea } from '../components/ui/textarea';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '../components/ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../components/ui/select';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '../components/ui/alert-dialog';
import { Plus, Pencil, Trash2, MessageSquareQuote, Star } from 'lucide-react';
import { useToast } from '../components/ui/use-toast';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const TestimonialsPage = () => {
  const { t } = useI18n();
  const { toast } = useToast();
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [selectedItem, setSelectedItem] = useState(null);
  const [formData, setFormData] = useState({
    client_name: '',
    client_title: '',
    client_company: '',
    client_photo: '',
    quote: '',
    rating: 5,
    status: 'draft',
    order: 0
  });

  const fetchItems = async () => {
    try {
      const response = await axios.get(`${API}/testimonials`);
      setItems(response.data);
    } catch (error) {
      console.error('Failed to fetch testimonials:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchItems();
  }, []);

  const handleOpenDialog = (item = null) => {
    if (item) {
      setSelectedItem(item);
      setFormData({
        client_name: item.client_name,
        client_title: item.client_title,
        client_company: item.client_company,
        client_photo: item.client_photo,
        quote: item.quote,
        rating: item.rating,
        status: item.status,
        order: item.order
      });
    } else {
      setSelectedItem(null);
      setFormData({
        client_name: '',
        client_title: '',
        client_company: '',
        client_photo: '',
        quote: '',
        rating: 5,
        status: 'draft',
        order: items.length
      });
    }
    setDialogOpen(true);
  };

  const handleSubmit = async () => {
    try {
      if (selectedItem) {
        await axios.put(`${API}/testimonials/${selectedItem.id}`, formData);
        toast({ title: t('save'), description: 'Testimonial bijgewerkt' });
      } else {
        await axios.post(`${API}/testimonials`, formData);
        toast({ title: t('add'), description: 'Testimonial toegevoegd' });
      }
      setDialogOpen(false);
      fetchItems();
    } catch (error) {
      console.error('Failed to save:', error);
      toast({ title: 'Error', description: 'Er ging iets mis', variant: 'destructive' });
    }
  };

  const handleDelete = async () => {
    try {
      await axios.delete(`${API}/testimonials/${selectedItem.id}`);
      toast({ title: t('delete'), description: 'Testimonial verwijderd' });
      setDeleteDialogOpen(false);
      setSelectedItem(null);
      fetchItems();
    } catch (error) {
      console.error('Failed to delete:', error);
      toast({ title: 'Error', description: 'Er ging iets mis', variant: 'destructive' });
    }
  };

  const renderStars = (rating) => {
    return [...Array(5)].map((_, i) => (
      <Star key={i} className={`w-4 h-4 ${i < rating ? 'star-filled fill-current' : 'star-empty'}`} />
    ));
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-[#129387]"></div>
      </div>
    );
  }

  return (
    <div className="animate-fadeIn">
      {/* Header */}
      <div className="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold text-white mb-2">{t('testimonials_title')}</h1>
          <p className="text-white/60">{t('testimonials_subtitle')}</p>
        </div>
        <Button onClick={() => handleOpenDialog()} className="btn-orange gap-2" data-testid="add-testimonial-btn">
          <Plus className="w-4 h-4" />
          {t('testimonials_add')}
        </Button>
      </div>

      {/* Content */}
      {items.length === 0 ? (
        <Card className="dashboard-card">
          <CardContent className="empty-state">
            <MessageSquareQuote className="empty-state-icon" />
            <h3 className="text-xl font-semibold text-white mb-2">{t('testimonials_empty')}</h3>
            <p className="text-white/60 mb-4">{t('testimonials_empty_desc')}</p>
            <Button onClick={() => handleOpenDialog()} className="btn-orange gap-2">
              <Plus className="w-4 h-4" />
              {t('testimonials_add')}
            </Button>
          </CardContent>
        </Card>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {items.map((item) => (
            <Card key={item.id} className="dashboard-card" data-testid={`testimonial-item-${item.id}`}>
              <CardContent className="p-6">
                <div className="flex items-start justify-between mb-4">
                  <div className="flex items-center gap-3">
                    {item.client_photo ? (
                      <img src={item.client_photo} alt={item.client_name} className="w-12 h-12 rounded-full object-cover" />
                    ) : (
                      <div className="w-12 h-12 rounded-full bg-[#f59d0e]/20 flex items-center justify-center text-[#f59d0e] font-semibold">
                        {item.client_name.charAt(0).toUpperCase()}
                      </div>
                    )}
                    <div>
                      <h3 className="font-semibold text-white">{item.client_name}</h3>
                      {(item.client_title || item.client_company) && (
                        <p className="text-sm text-white/60">
                          {item.client_title}{item.client_title && item.client_company && ' @ '}{item.client_company}
                        </p>
                      )}
                    </div>
                  </div>
                  <span className={`px-2 py-1 rounded text-xs font-medium ${item.status === 'published' ? 'badge-teal' : 'badge-draft'}`}>
                    {item.status === 'published' ? t('published') : t('draft')}
                  </span>
                </div>
                
                <div className="flex gap-1 mb-3">
                  {renderStars(item.rating)}
                </div>
                
                <blockquote className="text-white/80 italic mb-4">
                  "{item.quote}"
                </blockquote>
                
                <div className="flex items-center gap-2">
                  <Button 
                    variant="outline" 
                    size="sm" 
                    onClick={() => handleOpenDialog(item)}
                    className="flex-1 border-white/10 text-white hover:bg-white/5"
                    data-testid={`edit-testimonial-${item.id}`}
                  >
                    <Pencil className="w-4 h-4 mr-1" />
                    {t('edit')}
                  </Button>
                  <Button 
                    variant="outline" 
                    size="sm" 
                    onClick={() => { setSelectedItem(item); setDeleteDialogOpen(true); }}
                    className="border-red-500/20 text-red-400 hover:bg-red-500/10"
                    data-testid={`delete-testimonial-${item.id}`}
                  >
                    <Trash2 className="w-4 h-4" />
                  </Button>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      )}

      {/* Edit/Add Dialog */}
      <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
        <DialogContent className="bg-[#1c2336] border-white/10 text-white max-w-lg">
          <DialogHeader>
            <DialogTitle>{selectedItem ? t('testimonials_edit') : t('testimonials_add')}</DialogTitle>
          </DialogHeader>
          <div className="space-y-4 py-4">
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('testimonials_client_name')}</Label>
                <Input
                  value={formData.client_name}
                  onChange={(e) => setFormData({ ...formData, client_name: e.target.value })}
                  className="input-dark"
                  data-testid="testimonial-name-input"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('testimonials_client_title')}</Label>
                <Input
                  value={formData.client_title}
                  onChange={(e) => setFormData({ ...formData, client_title: e.target.value })}
                  className="input-dark"
                  placeholder="CEO, Eigenaar, etc."
                  data-testid="testimonial-title-input"
                />
              </div>
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('testimonials_client_company')}</Label>
                <Input
                  value={formData.client_company}
                  onChange={(e) => setFormData({ ...formData, client_company: e.target.value })}
                  className="input-dark"
                  data-testid="testimonial-company-input"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('testimonials_rating')}</Label>
                <Select value={String(formData.rating)} onValueChange={(v) => setFormData({ ...formData, rating: parseInt(v) })}>
                  <SelectTrigger className="input-dark" data-testid="testimonial-rating-select">
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent className="bg-[#1c2336] border-white/10">
                    {[5, 4, 3, 2, 1].map(n => (
                      <SelectItem key={n} value={String(n)}>{n} {n === 1 ? 'ster' : 'sterren'}</SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
            </div>
            <div className="space-y-2">
              <Label className="text-white/80">{t('testimonials_quote')}</Label>
              <Textarea
                value={formData.quote}
                onChange={(e) => setFormData({ ...formData, quote: e.target.value })}
                className="input-dark min-h-24"
                placeholder="Wat zei de klant over je diensten?"
                data-testid="testimonial-quote-input"
              />
            </div>
            <div className="space-y-2">
              <Label className="text-white/80">{t('status')}</Label>
              <Select value={formData.status} onValueChange={(v) => setFormData({ ...formData, status: v })}>
                <SelectTrigger className="input-dark" data-testid="testimonial-status-select">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent className="bg-[#1c2336] border-white/10">
                  <SelectItem value="draft">{t('draft')}</SelectItem>
                  <SelectItem value="published">{t('published')}</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setDialogOpen(false)} className="border-white/10 text-white hover:bg-white/5">
              {t('cancel')}
            </Button>
            <Button onClick={handleSubmit} className="btn-orange" data-testid="testimonial-save-btn">
              {t('save')}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Delete Confirmation */}
      <AlertDialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
        <AlertDialogContent className="bg-[#1c2336] border-white/10">
          <AlertDialogHeader>
            <AlertDialogTitle className="text-white">{t('delete')}</AlertDialogTitle>
            <AlertDialogDescription className="text-white/60">
              {t('testimonials_delete_confirm')}
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel className="border-white/10 text-white hover:bg-white/5">{t('cancel')}</AlertDialogCancel>
            <AlertDialogAction onClick={handleDelete} className="bg-red-500 hover:bg-red-600">
              {t('delete')}
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
};

export default TestimonialsPage;
